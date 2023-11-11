<?php

namespace app\models;

use app\components\Database;

class Products
{
    public static function all()
    {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT * FROM products'); // выполнение без параметров
        return $stmt->fetchAll();
    }

    public function one($id)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE `id` = :id'); // подготовка запроса с параметрами
        $stmt->execute(['id' => $id]); // выполнение запроса с передаными параметрами
        return $stmt->fetch();
    }
}
