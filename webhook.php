<?php

require_once __DIR__ . "/vendor/autoload.php";

use app\components\Telegram;

$data = Telegram::getInputData();

if (!$data['message']['chat']['id'] || $data['message']['chat']['id'] !== Telegram::CHAT_ID) {
    exit();
}

file_put_contents(__DIR__, '/webhook.log', print_r($data, 1) . PHP_EOL . PHP_EOL, FILE_APPEND);

Telegram::sendMessage($data['message']['text']);
