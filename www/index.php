<?php
    require_once '../vendor/autoload.php';
    $config = require_once '../src/config/config.php';
?>

<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Coding Challenge VTEX</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <? if (PROD): ?>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <? else: ?>
        <link rel="stylesheet" href="/lib/bootstrap/bootstrap.css">
    <? endif ?>

    <link rel="stylesheet" href="/main.css">
</head>

<body>
<div class="container">
    <div class="row">
        <h1>Dev Shop</h1>
    </div>

    <div id="cart-container"></div>

</div> <!-- /container -->

<? if (PROD): ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<? else: ?>
    <script src="/lib/jquery/jquery-1.11.3.js"></script>
    <script src="/lib/bootstrap/bootstrap.js"></script>
<? endif ?>

<script src="/main.js"></script>
</body>
</html>
