<?php

namespace app\components;

class Telegram
{
    const API_TOKEN = '6489457753:AAFhFDF4dhmv6wtA167OxwJy0___N0QJSjw';
    const CHAT_ID = '';

    public static function getInputData()
    {
        $input = file_get_contents('php://input');
        if ($input) {
            return $data = json_decode($input, true);
        }
        return [];
    }

    public static function apiRequest($method, $params)
    {
        $url = 'https://api.telegram.org/bot' . self::API_TOKEN . '/' . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        culr_close($ch);

        return json_decode($result, true);
    }

    public static function sendMessage($text, $keyboard = null)
    {
        $params = [
            'chat_id' => static::CHAT_ID,
            'text' => $text,
        ];
        if ($keyboard) {
            $reply_markup = [
                'inline_keyboard' => $keyboard,
            ];
            $params['reply_markup'] = json_decode($reply_markup);
        }
        static::apiRequest('sendMessage', $params);
    }

    public static function editMessageKeyboard($message_id, $keyboard = null)
    {
        $params = [
            'chat_id' => static::CHAT_ID,
            'message_id' => $message_id,
        ];
        if ($keyboard) {
            $reply_markup = [
                'inline_keyboard' => $keyboard,
            ];
            $params['reply_markup'] = json_decode($reply_markup);
        }
        static::apiRequest('editMessageReplyMarkup', $params);
    }

    public static function answerCallbackQuery ($callback_query_id){
        $params = [
            'callback_query_id' => $callback_query_id,
        ];
        static:: apiRequest('answerCallbackQuery', $params);
    }
}


