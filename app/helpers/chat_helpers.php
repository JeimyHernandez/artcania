<?php
function formatChatMessage(string $msg): string {
    $msg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
    $msg = preg_replace('/(https?:\/\/[^\s]+)/','<a href="$1" target="_blank" rel="noopener">$1</a>',$msg);
    return nl2br($msg);
}
function chatTimeAgo(string $datetime): string {
    $diff = time() - strtotime($datetime);
    if ($diff < 60)     return 'ahora';
    if ($diff < 3600)   return round($diff/60).' min';
    if ($diff < 86400)  return round($diff/3600).' h';
    return date('d/m', strtotime($datetime));
}
function userCanChat(int $userId1, int $userId2): bool { return $userId1 !== $userId2; }
