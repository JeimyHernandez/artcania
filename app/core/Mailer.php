<?php
/**
 * Mailer.php – PHP 7.4
 * Envía correos usando la Gmail API con OAuth 2.0.
 * NO requiere SMTP, NO requiere App Password, NO requiere librerías externas.
 *
 * CONFIGURACIÓN (config/app.php → 'mail'):
 *   oauth_email         → tu cuenta Gmail (noreplyartcania@gmail.com)
 *   oauth_client_id     → Client ID de Google Cloud Console
 *   oauth_client_secret → Client Secret de Google Cloud Console
 *   oauth_refresh_token → Se obtiene ejecutando /oauth_mail_setup.php UNA SOLA VEZ
 *
 * PASOS INICIALES:
 *   1. En Google Cloud Console → Biblioteca → habilita "Gmail API"
 *   2. Abre en el navegador: http://tu-sitio/artcania/oauth_mail_setup.php
 *   3. Autoriza con la cuenta Gmail que enviará correos
 *   4. El refresh_token se guarda automáticamente en config/app.php
 *   5. ¡Listo! Borra oauth_mail_setup.php del servidor.
 */
class Mailer
{
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';

    // URL de upload (para mensajes con cuerpo completo, que es nuestro caso)
    private const GMAIL_SEND = 'https://gmail.googleapis.com/upload/gmail/v1/users/me/messages/send?uploadType=media';

    // ── Método principal ──────────────────────────────────────────────────────

    /**
     * Envía un correo HTML usando la Gmail API con OAuth 2.0.
     */
    public static function send(string $to, string $subject, string $body, string $altBody = ''): bool
    {
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Logger::error("Mailer: email inválido: {$to}");
            return false;
        }

        $cfg = artcania_config();
        $mc  = $cfg['mail'];

        $clientId     = $mc['oauth_client_id']     ?? '';
        $clientSecret = $mc['oauth_client_secret']  ?? '';
        $refreshToken = $mc['oauth_refresh_token']  ?? '';
        $fromEmail    = $mc['oauth_email']          ?? '';
        $fromName     = $mc['from_name']            ?? 'Artcania';

        if (empty($clientId) || empty($clientSecret)) {
            Logger::error('Mailer: oauth_client_id u oauth_client_secret no configurados en config/app.php');
            return false;
        }
        if (empty($refreshToken)) {
            Logger::error('Mailer: oauth_refresh_token vacío. Abre /oauth_mail_setup.php para autorizarlo.');
            return false;
        }
        if (empty($fromEmail)) {
            Logger::error('Mailer: oauth_email no configurado en config/app.php');
            return false;
        }

        try {
            $accessToken = self::getAccessToken($clientId, $clientSecret, $refreshToken);
            $altText     = $altBody ?: strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
            $mime        = self::buildMime($fromEmail, $fromName, $to, $subject, $body, $altText);

            self::sendViaApi($accessToken, $mime);

            Logger::info("Mailer: correo enviado a {$to} — {$subject}");
            return true;

        } catch (Throwable $e) {
            Logger::error("Mailer error para {$to}: " . $e->getMessage());
            return false;
        }
    }

    // ── OAuth 2.0 ─────────────────────────────────────────────────────────────

    /**
     * Intercambia el refresh token por un access token.
     * @throws RuntimeException
     */
    public static function getAccessToken(string $clientId, string $clientSecret, string $refreshToken): string
    {
        $response = self::httpPost(self::TOKEN_URL, [
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
        ]);

        if (empty($response['access_token'])) {
            $err = $response['error_description'] ?? ($response['error'] ?? json_encode($response));
            throw new RuntimeException("No se pudo obtener access token: {$err}");
        }

        return $response['access_token'];
    }

    /**
     * Intercambia un authorization code por tokens.
     * Usado por oauth_mail_setup.php
     * @throws RuntimeException
     */
    public static function exchangeCode(string $clientId, string $clientSecret, string $redirectUri, string $code): array
    {
        $response = self::httpPost(self::TOKEN_URL, [
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        if (empty($response['refresh_token'])) {
            $err = $response['error_description'] ?? ($response['error'] ?? json_encode($response));
            throw new RuntimeException("Error al intercambiar code por token: {$err}");
        }

        return $response;
    }

    // ── Construcción del mensaje MIME raw ─────────────────────────────────────

    /**
     * Construye el mensaje MIME en texto plano (no base64url).
     * La API de upload acepta el MIME directamente como cuerpo del POST.
     */
    private static function buildMime(
        string $fromEmail,
        string $fromName,
        string $to,
        string $subject,
        string $htmlBody,
        string $textBody
    ): string {
        $boundary = 'artcania_' . bin2hex(random_bytes(8));

        // Codificar asunto y nombre del remitente para soportar UTF-8
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFrom    = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

        $mime  = "MIME-Version: 1.0\r\n";
        $mime .= "From: {$encodedFrom} <{$fromEmail}>\r\n";
        $mime .= "To: {$to}\r\n";
        $mime .= "Subject: {$encodedSubject}\r\n";
        $mime .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
        $mime .= "\r\n";

        // Parte texto plano
        $mime .= "--{$boundary}\r\n";
        $mime .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $mime .= "Content-Transfer-Encoding: base64\r\n";
        $mime .= "\r\n";
        $mime .= chunk_split(base64_encode($textBody), 76, "\r\n");
        $mime .= "\r\n";

        // Parte HTML
        $mime .= "--{$boundary}\r\n";
        $mime .= "Content-Type: text/html; charset=UTF-8\r\n";
        $mime .= "Content-Transfer-Encoding: base64\r\n";
        $mime .= "\r\n";
        $mime .= chunk_split(base64_encode($htmlBody), 76, "\r\n");
        $mime .= "\r\n";

        $mime .= "--{$boundary}--\r\n";

        return $mime;
    }

    // ── Envío via Gmail API (upload endpoint) ─────────────────────────────────

    /**
     * Envía el mensaje MIME directamente a la Gmail API upload endpoint.
     * Content-Type: message/rfc822  (el cuerpo ES el mensaje MIME completo)
     *
     * @throws RuntimeException
     */
    private static function sendViaApi(string $accessToken, string $rawMime): void
    {
        $url = self::GMAIL_SEND;

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $rawMime,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: message/rfc822',
                    'Content-Length: ' . strlen($rawMime),
                ],
            ]);
            $body   = curl_exec($ch);
            $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err    = curl_error($ch);
            curl_close($ch);

            if ($err) {
                throw new RuntimeException("cURL error: {$err}");
            }

        } else {
            // Fallback: file_get_contents + stream_context
            $ctx = stream_context_create(['http' => [
                'method'        => 'POST',
                'header'        => implode("\r\n", [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: message/rfc822',
                    'Content-Length: ' . strlen($rawMime),
                ]) . "\r\n",
                'content'       => $rawMime,
                'timeout'       => 30,
                'ignore_errors' => true,
            ]]);

            $body = @file_get_contents($url, false, $ctx);
            $status = 0;
            foreach ($http_response_header ?? [] as $h) {
                if (preg_match('/HTTP\/\S+\s+(\d+)/', $h, $m)) {
                    $status = (int)$m[1];
                }
            }

            if ($body === false) {
                throw new RuntimeException('file_get_contents falló al llamar Gmail API. Verifica que allow_url_fopen=On en php.ini.');
            }
        }

        // Verificar respuesta
        if ($status < 200 || $status >= 300) {
            $resp   = json_decode($body, true) ?? [];
            $apiErr = $resp['error']['message'] ?? ('HTTP ' . $status . ': ' . substr($body, 0, 300));
            throw new RuntimeException("Gmail API error {$status}: {$apiErr}");
        }
    }

    // ── HTTP POST helper ──────────────────────────────────────────────────────

    private static function httpPost(string $url, array $data): array
    {
        $payload = http_build_query($data);

        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 15,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            ]);
            $body = curl_exec($ch);
            $err  = curl_error($ch);
            curl_close($ch);
            if ($err) throw new RuntimeException("cURL POST {$url}: {$err}");
        } else {
            $ctx = stream_context_create(['http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content'       => $payload,
                'timeout'       => 15,
                'ignore_errors' => true,
            ]]);
            $body = @file_get_contents($url, false, $ctx);
            if ($body === false) throw new RuntimeException("HTTP POST falló: {$url}");
        }

        return json_decode($body, true) ?: [];
    }

    // ── Test de conexión ──────────────────────────────────────────────────────

    public static function testConexion(): array
    {
        $cfg = artcania_config();
        $mc  = $cfg['mail'];

        $result = [
            'oauth_email'         => $mc['oauth_email']          ?? '(no configurado)',
            'oauth_client_id'     => !empty($mc['oauth_client_id'])     ? '✓ configurado' : '✗ VACÍO',
            'oauth_client_secret' => !empty($mc['oauth_client_secret']) ? '✓ configurado' : '✗ VACÍO',
            'oauth_refresh_token' => !empty($mc['oauth_refresh_token']) ? '✓ configurado' : '✗ VACÍO — ejecuta /oauth_mail_setup.php',
            'ok'                  => false,
            'error'               => null,
        ];

        if (empty($mc['oauth_client_id']) || empty($mc['oauth_client_secret'])) {
            $result['error'] = 'Faltan oauth_client_id u oauth_client_secret en config/app.php';
            return $result;
        }
        if (empty($mc['oauth_refresh_token'])) {
            $result['error'] = 'oauth_refresh_token vacío. Abre /oauth_mail_setup.php en el navegador.';
            return $result;
        }

        try {
            $token = self::getAccessToken(
                $mc['oauth_client_id'],
                $mc['oauth_client_secret'],
                $mc['oauth_refresh_token']
            );
            $result['ok'] = !empty($token);
        } catch (Throwable $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }
}
