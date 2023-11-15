<?php

namespace app\models;

use app\components\Database;

class Orders
{
    public static function add($data)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("INSERT INTO orders(
                   `product_id`,
                   `product_name`,
                   `product_price`,
                   `product_count`,
                   `created_at`,
                   `phone`
) VALUES (
          :product_id,
          :product_name,
          :product_price,
          :product_count,
          :created_at,
          :phone
)");
        $stmt->execute($data);
        return $pdo->lastInsertId();
    }
}
