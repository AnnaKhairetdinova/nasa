<?php

require_once __DIR__ . '/vendor/autoload.php';


// Конфигурация
$bot_token = '7843638392:AAFVR7178mf6UzN8tiHJja2oesHUeqCh4ko'; // Замените на свой токен, полученный от @BotFather
$webhook_url = 'https://d60e-141-94-141-4.ngrok-free.app/index.php'; // Замените на ваш URL ngrok

// Логирование для отладки
function logMessage($message)
{
    file_put_contents('telegram_log.txt', date('Y-m-d H:i:s') . ': ' . $message . PHP_EOL, FILE_APPEND);
}

// Получение обновления от Telegram
$update = json_decode(file_get_contents('php://input'), true);
logMessage('Получено обновление: ' . json_encode($update, JSON_UNESCAPED_UNICODE));

// Проверка наличия сообщения
if (isset($update['message'])) {
    $message = $update['message'];
    $chat_id = $message['chat']['id'];
    $text = $message['text'] ?? '';
    $user_name = $message['from']['first_name'] ?? 'пользователь';

    // Обработка команд
    if (strpos($text, '/start') === 0) {
        $response_text = "Привет, $user_name! Я ваш бот. Чем могу помочь?";
        sendMessage($chat_id, $response_text);
    } elseif (strpos($text, '/help') === 0) {
        $response_text = "Список доступных команд:\n/start - Начать общение\n/help - Показать справку";
        sendMessage($chat_id, $response_text);
    } else {
        // Обработка обычных текстовых сообщений
        $response_text = "Вы сказали: $text";


        $client = new \Nasa\NasaAPI('sFszfSzQtqbfaXvEXXMnDi0tftkJWFIVOgG3GdAI', 'https://api.nasa.gov');

        $filter = new \Nasa\FilterCriteria();
//$filter->setEndDate(new DateTime('2025-03-04'));
//$filter->setStartDate(new DateTime('2025-03-03'));
        $filter->setCount(1);
        $data = $client->planetary($filter);
        print_r($data);

        sendMessage($chat_id, $data[0]['url'], getInlineKeyboard());
    }
} elseif (isset($update['callback_query'])) {
    // Обработка нажатий на инлайн-кнопки
    $callback_query = $update['callback_query'];
    $callback_data = $callback_query['data'];
    $chat_id = $callback_query['message']['chat']['id'];

    // Ответ на callback запрос
    answerCallbackQuery($callback_query['id'], "Вы выбрали: $callback_data");
    sendMessage($chat_id, "Ваш выбор: $callback_data");
}

// Функция для отправки сообщений
function sendMessage($chat_id, $text, $reply_markup = null)
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
    $result = file_get_contents($url, false, $context);

    logMessage('Ответ на сообщение: ' . $result);

    return $result;
}

// Функция для ответа на callback запросы
function answerCallbackQuery($callback_query_id, $text = null, $show_alert = false)
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
    $result = file_get_contents($url, false, $context);

    logMessage('Ответ на callback запрос: ' . $result);

    return $result;
}

// Пример создания инлайн-клавиатуры
function getInlineKeyboard()
{
    return json_encode([
        'inline_keyboard' => [
            [
                ['text' => 'Кнопка 1', 'callback_data' => 'button1'],
                ['text' => 'Кнопка 2', 'callback_data' => 'button2']
            ],
            [
                ['text' => 'Кнопка 3', 'callback_data' => 'button3']
            ]
        ]
    ]);
}

// Функция для установки вебхука (должна вызываться отдельно, один раз)
function setWebhook()
{
    global $bot_token, $webhook_url;

    $url = "https://api.telegram.org/bot$bot_token/setWebhook?url=$webhook_url";
    $result = file_get_contents($url);

    logMessage('Результат установки вебхука: ' . $result);

    return $result;
}


// Для установки вебхука раскомментируйте следующую строку и запустите скрипт один раз,
// затем закомментируйте обратно:
//echo setWebhook();

