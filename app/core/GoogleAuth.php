<?php
/**
 * GoogleAuth.php – PHP 7.4
 * Maneja el flujo OAuth 2.0 para "Acceder con Google".
 */
class GoogleAuth
{
    const SCOPES = 'openid email profile';

    public static function authUrl(): string
    {
        $gc    = artcania_config()['google_login'];
        $state = bin2hex(random_bytes(16));
        $_SESSION['google_oauth_state'] = $state;

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query(array(
            'client_id'     => $gc['client_id'],
            'redirect_uri'  => $gc['redirect_uri'],
            'response_type' => 'code',
            'scope'         => self::SCOPES,
            'state'         => $state,
            'access_type'   => 'online',
        ));
    }

    /**
     * @throws RuntimeException
     * @return array ['id_google','email','nombre','avatar']
     */
    public static function handleCallback(string $code, string $state): array
    {
        $saved = $_SESSION['google_oauth_state'] ?? '';
        unset($_SESSION['google_oauth_state']);

        if (empty($saved) || !hash_equals($saved, $state)) {
            throw new RuntimeException('Estado OAuth inválido (posible ataque CSRF).');
        }

        $gc     = artcania_config()['google_login'];
        $tokens = self::post('https://oauth2.googleapis.com/token', array(
            'code'          => $code,
            'client_id'     => $gc['client_id'],
            'client_secret' => $gc['client_secret'],
            'redirect_uri'  => $gc['redirect_uri'],
            'grant_type'    => 'authorization_code',
        ));

        if (empty($tokens['access_token'])) {
            $err = $tokens['error_description'] ?? ($tokens['error'] ?? 'respuesta vacía');
            throw new RuntimeException("Google rechazó el código: {$err}");
        }

        $profile = self::get(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            $tokens['access_token']
        );

        if (empty($profile['email'])) {
            throw new RuntimeException('Google no devolvió el email del usuario.');
        }
        if (empty($profile['email_verified'])) {
            throw new RuntimeException('El email de Google no está verificado.');
        }

        return array(
            'id_google' => $profile['sub'],
            'email'     => strtolower(trim($profile['email'])),
            'nombre'    => $profile['name'] ?? explode('@', $profile['email'])[0],
            'avatar'    => $profile['picture'] ?? '',
        );
    }

    private static function post(string $url, array $data): array
    {
        $datos_payload = http_build_query($data);
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $datos_payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_HTTPHEADER     => array('Content-Type: application/x-www-form-urlencoded'),
            ));
            $body = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
            if ($err) throw new RuntimeException("cURL POST: {$err}");
        } else {
            $ctx  = stream_context_create(array('http' => array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $datos_payload, 'timeout' => 15,
            )));
            $body = @file_get_contents($url, false, $ctx);
            if ($body === false) throw new RuntimeException("HTTP POST falló");
        }
        return json_decode($body, true) ?: array();
    }

    private static function get(string $url, string $token): array
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15,
                CURLOPT_HTTPHEADER     => array("Authorization: Bearer {$token}"),
            ));
            $body = curl_exec($ch); $err = curl_error($ch); curl_close($ch);
            if ($err) throw new RuntimeException("cURL GET: {$err}");
        } else {
            $ctx  = stream_context_create(array('http' => array(
                'method' => 'GET',
                'header' => "Authorization: Bearer {$token}\r\n",
                'timeout'=> 15,
            )));
            $body = @file_get_contents($url, false, $ctx);
            if ($body === false) throw new RuntimeException("HTTP GET falló");
        }
        return json_decode($body, true) ?: array();
    }
}
