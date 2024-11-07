<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable('../');
$dotenv->safeLoad();

require_once __DIR__ . '/vite.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal diary</title>

    <?= vite('_vendor-Dm7GrVsG.js') ?>
    <?= vite('main.js') ?>

</head>

<body>
    <div id="vue-app" />
</body>

</html>
