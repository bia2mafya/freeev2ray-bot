
<?php
$bot_token = '7586838595:AAHRFoImH2YFPkEeqEWpBngBDmuoEvSM9oY';
$chat_id = '@testfreevpn';

// URL برای دریافت داده‌ها
$proxy_url = 'https://raw.githubusercontent.com/MrMohebi/xray-proxy-grabber-telegram/refs/heads/master/collected-proxies/row-url/actives.txt';

// دریافت داده‌ها از URL
$proxies = file_get_contents($proxy_url);
$proxies = explode("\n", $proxies);

// تابع برای ارسال پیام به کانال تلگرام
function sendMessage($text) {
    global $bot_token, $chat_id;

    // تقسیم پیام‌های طولانی
    if (strlen($text) > 4096) {
        $messages = str_split($text, 4096);  // تقسیم به قطعات کوچک‌تر
        foreach ($messages as $message) {
            file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message) . "&parse_mode=Markdown");
        }
    } else {
        file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($text) . "&parse_mode=Markdown");
    }
}

// حذف قسمت بعد از علامت "#"
$updated_proxies = [];
foreach ($proxies as $proxy) {
    $proxy = trim($proxy);
    if (empty($proxy)) continue;
    
    // حذف هر چیزی بعد از #
    $proxy = preg_replace('/#.*$/', '', $proxy);
    
    // اضافه کردن متن جدید بعد از "#"
    $proxy .= "#👉🆔 @Freeev2ray📡";
    
    // ذخیره لینک‌ها به فایل previous_links.txt
    file_put_contents('previous_links.txt', $proxy . PHP_EOL, FILE_APPEND);
    
    // ذخیره لینک‌ها در آرایه برای ارسال به تلگرام
    $updated_proxies[] = $proxy;
}

// ارسال لینک‌ها به کانال تلگرام
sendMessage(implode("\n", array_slice($updated_proxies, 0, 10)));  // ارسال تنها 10 لینک به کانال
?>
