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

    public static function togglesStatus()
    {
        $pdo = Database::connect();

        $stmt = $pdo->prepare('SELECT  `status` FROM orders WHERE id=:id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row) {
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

    public static function delete()
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('DELETE FROM orders WHERE if=:id');
        $stmt->execute(['id' => $id]);
    }

    public static function one()
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM order WHERE id=:id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function all($where=null, $params=null)
    {
        $pdo = Database::connect();
        $sql_where = '';
        if($where){
            $sql_where = 'WHERE' . implode(' AND', $where);
        }
        $stmt = $pdo->prepare('SELECT * FROM orders ' . $sql_where . ' order by `creates_at` desc limit 10');
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
