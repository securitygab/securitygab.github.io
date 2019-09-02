<?php

    if (!isset($_GET['type'])) die("Type not set");

    if (!isset($_GET['access'])) header("Location: /");

    require '../includes/init.php';

    $settings = $database->query("SELECT * FROM `settings`")->single();

    if ($_GET['access'] != $settings['admin']) header("Location: /");

    $result = $database->query("SELECT * FROM `products` WHERE `productType`=:id AND `used`=0 LIMIT :amount")->bind('id', $_GET['type'])->bind('amount', (int)$_GET['limit'])->resultset();

    $accounts = [];

    foreach ($result as $row) {
        $database->query("UPDATE `products` SET `used`=1 WHERE `id`=:id")->bind('id', $row['id'])->execute();
        $accounts[ sizeof($accounts) ] = $row['value'];
    }

    echo json_encode($accounts);