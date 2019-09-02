<?php

    if (!isset($_GET['access'])) header("Location: /");

    require '../includes/init.php';

    $settings = $database->query("SELECT * FROM `settings`")->single();

    if ($_GET['access'] != $settings['admin']) header("Location: /");

    foreach (array_keys($settings) as $key) {
        $database->query("UPDATE `settings` SET `$key`=:pkey WHERE 1")->bind('pkey', $_POST[ $key ])->execute();
    }
