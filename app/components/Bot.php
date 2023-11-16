<?php

namespace app\components;

use app\models\Orders;

class Bot
{
    public static function processMessage($message)
    {
        if (preg_match('^/[a-z]+[\s_]+.+$', $message['text'], $matches)) {

        }
    }

    public static function processCallback($callback_query)
    {
        $message_id = $callback_query['message']['message_id'];
        $data = json_decode($callback_query['data'], true);

        Telegram::answerCallbackQuery($callback_query['id']);

        if (!isset($message_id, $data['command'])) {
            exit();
        }

        switch ($data['command']) {
            case 'toggle>order_status':
                $status = Orders::togglesStatus($data['id']);
                $keyboard = static::getOrderKeyboard($data['id'], $status);
                Telegram::editMessageKeyboard($message_id, $keyboard);
                break;
            case 'delete_order':
                $message = 'Удалить заказ #' . $data['id'] . '?';
                $keyboard = static::getDeleteOrderKeyboard($data['id']);
                Telegram::sendMessage($message, $keyboard);
            case 'delete_order_cansel':
                Telegram::deleteMessage($message_id);
                break;
            case 'delete_order_confirm':
                Orders::delete($data['id']);
                Telegram::deleteMessage($message_id);
                $message = 'Заказ #' . $data['id'] . ' удален';
                Telegram::sendMessage($message);
        }
    }

    public static function getOrderKeyboard($order_id, $status)
    {
        $status_str = $status ? 'Выполнен' : 'Новый';
        return = [
        [
            ['text' => $status_str, 'callback_data' => json_decode(['command' => 'toggle_order_status', 'id' => $order_id])],
            ['text' => 'Удалить', 'callback_data' => json_decode(['command' => 'delete_order', 'id' => $order_id])],
        ]
    ];
    }

    public static function getDeleteOrderKeyboard()
    {
        ['text' => 'Да', 'callback_data' => json_decode(['command' => 'delete_order_confirm', 'id' => $order_id])],
        ['text' => 'Отмена', 'callback_data' => json_decode(['command' => 'delete_order')],
    }

    public static function answerCallbackQuery($callback_query_id)
    {
        $params = [
            'callback_query_id' => $callback_query_id,
        ];
        static::apiRequest('answerCallbackQuery');
    }

    public static function deleteMessage($message_id)
    {
        $params = [
            'chat_id' => static::CHAT_ID,
            'message_id' => $message_id,
        ];
        static::apiRequest('delete', $params);
    }

    public static function delete()
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('DELETE FROM orders WHERE if=:id');
        $stmt->execute(['id' => $id]);
    }
}

