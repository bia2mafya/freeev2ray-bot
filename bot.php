
<?php
// توکن ربات تلگرام و شناسه کانال به طور مستقیم در اینجا وارد می‌شود
$telegramBotToken = '7586838595:AAHRFoImH2YFPkEeqEWpBngBDmuoEvSM9oY';
$chatId = '@testfreevpn';

// آدرس URL که حاوی لیست لینک‌ها است
$url = 'https://raw.githubusercontent.com/MrMohebi/xray-proxy-grabber-telegram/refs/heads/master/collected-proxies/row-url/actives.txt';

// مسیر فایل ذخیره لینک‌های قبلی
$previousLinksFile = 'previous_links.txt';

// اگر فایل لینک‌های قبلی وجود ندارد، ایجاد شود
if (!file_exists($previousLinksFile)) {
    file_put_contents($previousLinksFile, '');
}

// خواندن لینک‌های قبلی از فایل
$previousLinks = file($previousLinksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// دریافت محتوای فایل از URL
$content = file_get_contents($url);

// بررسی اینکه آیا محتوای فایل به درستی خوانده شده است
if ($content === false) {
    echo "Error reading the content from the URL!";
    exit();
}

// تقسیم محتوای فایل به خط‌ها
$lines = explode("\n", $content);

// ذخیره کل لینک‌ها که ارسال خواهند شد
$finalMessage = "";

// پردازش هر خط
foreach ($lines as $line) {
    // حذف بخش بعد از # ولی علامت # باقی می‌ماند
    if (strpos($line, '#') !== false) {
        $line = substr($line, 0, strpos($line, '#')); // جدا کردن تا علامت #
    }
    $line = trim($line);  // حذف فاصله‌ها

    // بررسی اینکه آیا لینک خالی نیست و قبلاً ارسال نشده
    if (!empty($line) && !in_array($line, $previousLinks)) {
        // اضافه کردن متن جدید بعد از #
        $finalMessage .= $line . ' 👉🆔 @Freeev2ray📡' . "\n";

        // ذخیره لینک ارسال‌شده در فایل برای جلوگیری از ارسال مجدد
        file_put_contents($previousLinksFile, $line . PHP_EOL, FILE_APPEND);
    }
}

// اگر پیامی آماده شده است، آن را به تلگرام ارسال کن
if (!empty($finalMessage)) {
    // Escape کردن کاراکترهای خاص برای استفاده در MarkdownV2
    $finalMessage = escapeMarkdownV2($finalMessage);

    // ارسال پیام به تلگرام با فرمت mono (متن داخل backticks)
    sendToTelegram("`" . $finalMessage . "`");
}

// تابع ارسال پیام به تلگرام
function sendToTelegram($message) {
    global $telegramBotToken, $chatId;
    
    $url = "https://api.telegram.org/bot$telegramBotToken/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'MarkdownV2'  // تنظیم حالت فرمت برای ارسال متن به صورت mono
    ];

    // استفاده از cURL برای ارسال درخواست به API تلگرام
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);  // تنظیم زمان تایم‌اوت برای درخواست

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

// تابع برای escape کردن کاراکترهای خاص در MarkdownV2
function escapeMarkdownV2($text) {
    $search = ['\\', '*', '_', '{', '}', '[', ']', '(', ')', '#', '+', '-', '.', '!', '~'];
    $replace = ['\\\\', '\\*', '\\_', '\\{', '\\}', '\\[', '\\]', '\\(', '\\)', '\\#', '\\+', '\\-', '\\.', '\\!', '\\~'];
    return str_replace($search, $replace, $text);
}
?>

