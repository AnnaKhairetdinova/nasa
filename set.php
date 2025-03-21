<?php

require __DIR__ . '/vendor/autoload.php';

$bot_username = 'nasa_bot';
$bot_api_key = '7843638392:AAFVR7178mf6UzN8tiHJja2oesHUeqCh4ko';
$webhook_url = 'https://d60e-141-94-141-4.ngrok-free.app/index.php';

try {
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    $result = $telegram->setWebhook($webhook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}
