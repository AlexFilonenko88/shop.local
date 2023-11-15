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

    public static function togglesStatus () {
        $pdo = Database::connect();

        $stmt = $pdo -> prepare('SELECT  `status` FROM orders WHERE id=:id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if($row){
            $status = $row['status'] ? 0 : 1;

            $stmt = $pdo->prepare('UPDATE orders SET  `status` = :status `modified_at` = :modified_at WHERE `id` = :id');
            $stmt->execute([
                'status' => $status,
                'id' => $id,
                'modified_at' => date('Y-m-d H:i:s'),
            ]);

            return $status;
        }
    }
}
