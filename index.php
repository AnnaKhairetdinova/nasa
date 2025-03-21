<?php

require_once __DIR__ . '/vendor/autoload.php';

$bot_token = '';
$webhook_url = 'https://d60e-141-94-141-4.ngrok-free.app/index.php';

function sendMessage($chat_id, $text, $reply_markup = null): false|string
{
    global $bot_token;

    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];

    if ($reply_markup) {
        $data['reply_markup'] = $reply_markup;
    }

    $url = "https://api.telegram.org/bot$bot_token/sendMessage";

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

function answerCallbackQuery($callback_query_id, $text = null, $show_alert = false): false|string
{
    global $bot_token;

    $data = [
        'callback_query_id' => $callback_query_id,
        'show_alert' => $show_alert
    ];

    if ($text) {
        $data['text'] = $text;
    }

    $url = "https://api.telegram.org/bot$bot_token/answerCallbackQuery";

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

function setWebhook(): false|string
{
    global $bot_token, $webhook_url;

    $url = "https://api.telegram.org/bot$bot_token/setWebhook?url=$webhook_url";
    return file_get_contents($url);
}
