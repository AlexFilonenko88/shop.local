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

    public static function sendMessage($text)
    {
        $params = [
            'chat_id' => static::CHAT_ID,
            'text' => $text,
        ];
        static::apiRequest('sendMessage', $params);
    }
}

