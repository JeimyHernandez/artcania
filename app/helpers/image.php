<?php
function resizeImage(string $src, string $dst, int $maxW = 800, int $maxH = 600, int $quality = 85): bool {
    $info = @getimagesize($src);
    if (!$info) return false;
    $w = $info[0]; $h = $info[1]; $type = $info[2];

    switch ($type) {
        case IMAGETYPE_JPEG: $img = imagecreatefromjpeg($src); break;
        case IMAGETYPE_PNG:  $img = imagecreatefrompng($src);  break;
        case IMAGETYPE_GIF:  $img = imagecreatefromgif($src);  break;
        case IMAGETYPE_WEBP: $img = function_exists('imagecreatefromwebp') ? imagecreatefromwebp($src) : false; break;
        default: return false;
    }
    if (!$img) return false;

    $ratio = min($maxW / $w, $maxH / $h, 1);
    $nw = (int)($w * $ratio);
    $nh = (int)($h * $ratio);
    $thumb = imagecreatetruecolor($nw, $nh);

    if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }
    imagecopyresampled($thumb, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);

    switch ($type) {
        case IMAGETYPE_JPEG: $ok = imagejpeg($thumb, $dst, $quality); break;
        case IMAGETYPE_PNG:  $ok = imagepng($thumb, $dst);            break;
        case IMAGETYPE_GIF:  $ok = imagegif($thumb, $dst);            break;
        case IMAGETYPE_WEBP: $ok = function_exists('imagewebp') ? imagewebp($thumb, $dst, $quality) : false; break;
        default: $ok = false;
    }
    imagedestroy($img);
    imagedestroy($thumb);
    return (bool)$ok;
}

function miniaturaPath(string $original, string $suffix = '_thumb'): string {
    $info = pathinfo($original);
    return $info['dirname'] . '/' . $info['filename'] . $suffix . '.' . $info['extension'];
}
