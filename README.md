Free V2Ray Proxy Bot
A PHP-based Telegram bot that fetches V2Ray proxy links from a remote source, formats them, and sends updated links to a specified Telegram channel.
Features
• Fetches proxy links from a specified URL.
• Formats links by removing unnecessary parts and appending a custom message.
• Sends the latest 10 updated links to a Telegram channel.
• Ensures no duplicate messages are sent by storing already sent links.

Prerequisites
• A Telegram bot token. You can create one using BotFather.
• A Telegram channel where the bot will post messages.
• PHP installed on your server or hosting environment.

Installation
1. Clone the repository to your local machine or hosting environment:
bash
Copy code
git clone https://github.com/yourusername/your-repository.git
cd your-repository
2. Update the following variables in bot.php:
php
Copy code
$bot_token = 'YOUR_BOT_TOKEN';
$chat_id = '@YOUR_CHANNEL_NAME';
3. Ensure previous_links.txt exists in the same directory as bot.php. If not, create it manually:
bash
Copy code
touch previous_links.txt
4. Run the bot using PHP:
bash
Copy code
php bot.php

Automation with GitHub Actions
1. Add the bot token and channel name as repository secrets:
o BOT_TOKEN for your bot token.
o CHAT_ID for your Telegram channel ID.
2. Use the provided GitHub Action workflow file (.github/workflows/main.yml) to automate the script execution.
3. The bot will execute every hour, fetch new links, and send updates to your Telegram channel.

File Descriptions
• bot.php: The main script for fetching, processing, and sending V2Ray links.
• previous_links.txt: A file that stores the previously sent links to avoid duplication.
• .github/workflows/main.yml: A GitHub Actions workflow for automating the bot execution.

Customization
• Modify the $proxy_url variable in bot.php to change the source of V2Ray proxy links.
• Update the format or appended message in the foreach loop for links to customize the output.

Example Output
bash
Copy code
📢 Latest V2Ray Proxies:
vless://example1#👉🆔 @Freeev2ray📡
vless://example2#👉🆔 @Freeev2ray📡
...
Stay connected!

License
This project is licensed under the MIT License. See the LICENSE file for details.

Feel free to update this README.md to include more details or adapt it for your project. 😊

