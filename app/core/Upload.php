<?php
class Upload {
    /** @var array */
    private $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    /** @var int */
    private $maxSize = 10485760; // 10 MB
    /** @var string */
    private $dir    = '';

    public function __construct(string $subDir = 'Originales/imagen') {
        $this->dir = BASE_PATH . '/media/' . trim($subDir, '/') . '/';
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0755, true);
        }
    }

    public function allowVideo() {
        $this->allowed[] = 'video/mp4';
        $this->allowed[] = 'video/webm';
        return $this;
    }

    public function maxMB(int $mb) {
        $this->maxSize = $mb * 1048576;
        return $this;
    }

    public function handle(array $file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Error al subir archivo. Código: ' . $file['error']);
        }
        if ($file['size'] > $this->maxSize) {
            throw new RuntimeException('El archivo excede el tamaño máximo permitido.');
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $this->allowed, true)) {
            throw new RuntimeException('Tipo de archivo no permitido: ' . $mime);
        }

        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = bin2hex(random_bytes(16)) . '.' . $ext;
        $dest = $this->dir . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new RuntimeException('No se pudo mover el archivo al destino.');
        }

        return $name;
    }
}
