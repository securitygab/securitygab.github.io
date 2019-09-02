<?php

    if (!isset($_POST['type'])) die("Type not set");
    if (!isset($_POST['accounts'])) die("Accounts not set");

    if (!isset($_GET['access'])) header("Location: /");

    require '../includes/init.php';

    $settings = $database->query("SELECT * FROM `settings`")->single();

    if ($_GET['access'] != $settings['admin']) header("Location: /");

    foreach (explode("\n", $_POST['accounts']) as $row) {
        $database->query("INSERT INTO `products`(`id`, `productType`, `value`, `used`) VALUES (NULL,:ptype,:pvalue,:pused) ")
            ->bind('ptype', $_POST['type'])
            ->bind('pvalue', $row)
            ->bind('pused', 0)
            ->execute();
    }

    echo "Success!";