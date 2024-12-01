$apiToken = '7586838595:AAHRFoImH2YFPkEeqEWpBngBDmuoEvSM9oY';
$channelUsername = '@testfreevpn';
$dataUrl = 'https://raw.githubusercontent.com/MrMohebi/xray-proxy-grabber-telegram/refs/heads/master/collected-proxies/row-url/actives.txt';
$previousLinksFile = 'previous_links.txt';

// دانلود داده‌ها از URL
$data = file_get_contents($dataUrl);
if ($data === false) {
    die("Failed to fetch data from URL.\n");
}

// پردازش لینک‌ها و اصلاح آن‌ها
$lines = explode("\n", $data);
$processedLinks = [];
foreach ($lines as $line) {
    if (trim($line) === '') {
        continue;
    }
    $linkParts = explode('#', $line, 2);
    if (count($linkParts) > 1) {
        $processedLinks[] = $linkParts[0] . '#👉🆔 @Freeev2ray📡';
    }
}

// خواندن لینک‌های قبلی از فایل
$previousLinks = [];
if (file_exists($previousLinksFile)) {
    $previousLinks = file($previousLinksFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

// پیدا کردن لینک‌های جدید
$newLinks = array_diff($processedLinks, $previousLinks);
if (empty($newLinks)) {
    echo "No new links to send.\n";
    exit;
}

// ارسال لینک‌های جدید به کانال
$batchSize = 10; // تعداد لینک‌ها در هر پیام
$batches = array_chunk($newLinks, $batchSize);

foreach ($batches as $batch) {
    $message = "```\n" . implode("\n", $batch) . "\n```"; // ارسال در قالب Mono
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query([
        'chat_id' => $channelUsername,
        'text' => $message,
        'parse_mode' => 'MarkdownV2'
    ]));
    echo "Message sent: " . $response . "\n";
    sleep(1); // کمی تأخیر برای جلوگیری از محدودیت API
}

// به‌روزرسانی فایل لینک‌های قبلی
file_put_contents($previousLinksFile, implode("\n", array_merge($previousLinks, $newLinks)));
