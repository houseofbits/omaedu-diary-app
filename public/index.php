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

    <link rel="preload" href="assets/fonts/corbel.ttf" as="font" crossorigin="anonymous">
    <link rel="preload" href="assets/images/balti-ziedi.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/gaisas-spalvas.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/lashkrasa.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/pasteltoni.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/zalganzils.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/zilganas-spalvas.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/ainava.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/makoni.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/rozigas-spalvas.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/spalvas-roza.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/tirkizs.jpg" as="image" crossorigin="anonymous">
    <link rel="preload" href="assets/images/zilas-spalvas.jpg" as="image" crossorigin="anonymous">

    <?= vite('_vendor-Cueh1TB1.js') ?>
    <?= vite('main.js') ?>

</head>

<body>
    <div id="vue-app" />
</body>

</html>