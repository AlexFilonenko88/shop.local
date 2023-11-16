<?php

namespace app\components;

use app\models\Orders;
use app\models\Products;

class Bot
{
    public static function processMessage($message)
    {
        if (preg_match('~^/([a-z]+)(?:[\s_]+(.+))?$~', $message['text'], $matches)) {
            $command = $matches[1];
            if (isset($matches[2])) {
                $params = preg_split('~[\s]+~', $matches[2]);
            } else {
                $params = [];
            }
            switch ($command) {
                case 'order':
                    if (!$params) {
                        Telegram::sendMessage('Необходимо указать id заказа');
                        return;
                    }
                    $order = Orders::one($params[0]);
                    if (!$params) {
                        Telegram::sendMessage('Заказ с таким id не существует');
                        return;
                    }
                    $message = 'Заказ #' . $order . PHP_EOL .
                        'Товар: #' . $order['product_id'] . ' ' . $order['product_name'] . PHP_EOL .
                        'Количество: ' . $order['product_count'] . PHP_EOL .
                        'Цена: ' . $order['product_price'] . PHP_EOL .
                        'Сумма: ' . ($order['product_count'] * $order['product_price']) . PHP_EOL .
                        'Создан: ' . $order['created_at'] . PHP_EOL .
                        'Изменен: ' . ($order['modified_at']);
                    $keyboard = Bot(self::getOrderKeyboard($order['id'], $order));
                    Telegram::sendMessage($message, $keyboard);
                    break;
                case 'orders':
//                    file_put_contents(__DIR__ . '/log.txt', print_r($params,1));
                    $where = [];
                    $whee_params = [];
                    if (array_search('new', $params) !== false) {
                        $whee[] = 'status = 0';
                    } elseif (array_search('done', $params)  !== false) {
                        $whee[] = 'status = 1';
                    }
                    if (array_search('today', $params)  !== false) {
                        $whee[] = 'create_at >= :date';
                        $whee_params['date'] = date('Y-m-d');
                    } elseif (array_search('week', $params)  !== false) {
                        $whee[] = 'create_at >= :date';
                        $whee_params['date'] = date('Y-m-d', strtotime('-7 day'));
                    } elseif (array_search('month', $params)  !== false) {
                        $whee[] = 'create_at >= :date';
                        $whee_params['date'] = date('Y-m-d', strtotime('-1 month'));
                    }
                    foreach ($params as $param) {
                        if (is_numeric($param)) {
                            $whee[] = 'product_id >= :id';
                            $whee_params['product'] = $param;
                        }
                    }
                    $orders = Orders::all($where, $whee_params);
                    $orders = array_reverse($orders);
                    $message = '';
                    foreach ($orders as $order) {
                        $message .= 'Знак #' . $order['id'] . PHP_EOL .
                            'Сумма: ' . ($order['product_count'] * $order['product_id']) . PHP_EOL
                            'Создан: ' . $order['created_at'] . PHP_EOL .
                            '/order_' . $order['id'] . PHP_EOL . PHP_EOL;
                    }
                    break;
            }
        }
    }

    public
    static function processCallback($callback_query)
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

    public
    static function getOrderKeyboard($order_id, $status)
    {
        $status_str = $status ? 'Выполнен' : 'Новый';
        return = [
        [
            ['text' => $status_str, 'callback_data' => json_decode(['command' => 'toggle_order_status', 'id' => $order_id])],
            ['text' => 'Удалить', 'callback_data' => json_decode(['command' => 'delete_order', 'id' => $order_id])],
        ]
    ];
    }

    public
    static function getDeleteOrderKeyboard()
    {
        ['text' => 'Да', 'callback_data' => json_decode(['command' => 'delete_order_confirm', 'id' => $order_id])],
        ['text' => 'Отмена', 'callback_data' => json_decode(['command' => 'delete_order')],
    }

    public
    static function answerCallbackQuery($callback_query_id)
    {
        $params = [
            'callback_query_id' => $callback_query_id,
        ];
        static::apiRequest('answerCallbackQuery');
    }

    public
    static function deleteMessage($message_id)
    {
        $params = [
            'chat_id' => static::CHAT_ID,
            'message_id' => $message_id,
        ];
        static::apiRequest('delete', $params);
    }


}

