<?php
// Redirect to unified chat view
$cfg = artcania_config();
$url = rtrim($cfg['url'], '/') . '/chat';
header('Location: ' . $url, true, 302);
exit;
