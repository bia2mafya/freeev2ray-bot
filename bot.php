<?php
// توکن ربات تلگرام و شناسه کانال به طور مستقیم در اینجا وارد می‌شود
$telegramBotToken = '7586838595:AAHRFoImH2YFPkEeqEWpBngBDmuoEvSM9oY';
$chatId = '@freeev2ray';

// آدرس URL که حاوی لیست لینک‌ها است
$url = 'https://raw.githubusercontent.com/MrMohebi/xray-proxy-grabber-telegram/refs/heads/master/collected-proxies/row-url/actives.txt';

// دریافت محتوای فایل از URL
$content = file_get_contents($url);

// بررسی اینکه آیا محتوای فایل به درستی خوانده شده است
if ($content === false) {
    echo "Error reading the content from the URL!";
    exit();
}

// تقسیم محتوای فایل به خط‌ها
$lines = explode("\n", $content);

// تابع ارسال پیام به تلگرام
function sendToTelegram($message) {
    global $telegramBotToken, $chatId;
    
    $url = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    // استفاده از cURL برای ارسال درخواست به API تلگرام
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);  // تنظیم زمان تایم‌اوت برای درخواست
    curl_setopt($ch, CURLOPT_VERBOSE, true); // فعال‌سازی cURL برای دیباگ
    curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));  // برای بررسی خطاها

    $response = curl_exec($ch);

    // بررسی اینکه آیا خطایی در ارسال پیام وجود دارد یا نه
    if(curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        echo 'Message sent successfully!';
        var_dump($response); // چاپ پاسخ API برای بررسی
    }

    curl_close($ch);
}

// پردازش هر خط
foreach ($lines as $line) {
    // حذف بخش بعد از # و اضافه کردن متن جدید
    $line = explode('#', $line)[0]; // جدا کردن تا علامت # 
    $line = trim($line);  // حذف فاصله‌ها

    // بررسی اینکه آیا لینک قبلاً ارسال شده است
    if (!empty($line)) {
        // اضافه کردن متن جدید بعد از # 
        $message = $line . ' 👉🆔 @Freeev2ray📡';

        // ارسال پیام به تلگرام
        sendToTelegram($message);
    }
}
?>
