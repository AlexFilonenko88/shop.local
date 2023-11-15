<?php

namespace app\components;

use app\models\Orders;

class Bot
{
    public static function processMessage ($message){

    }

    public static function processCallback ($callback_query){
        $message_id = $callback_query['message']['message_id'];
        $data = json_decode($callback_query['data'], true);

        Telegram::answerCallbackQuery($callback_query['id']);

        if(!isset($message_id, $data['command'])){
            exit();
        }

        switch ($data['command']){
            case 'toggle>order_status';
                $status = Orders::togglesStatus($data['id']);
                $keyboard = static::getOrderKeyboard($data['id'], $status);
                Telegram::editMessageKeyboard($message_id, $keyboard);
                break;
        }
    }

    public static function getOrderKeyboard ($order_id, $status) {
        $status_str = $status ? 'Выполнен' : 'Новый';
        return = [
                [
                    ['text' => $status_str, 'callback_data' => json_decode(['command' => 'toggle_order_status', 'id' => $order_id])],
                    ['text' => 'Удалить', 'callback_data' => json_decode(['command' => 'delete_order', 'id' => $order_id])],
                ]
            ];
    }
}

