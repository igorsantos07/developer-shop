<?php
    require_once '../vendor/autoload.php';
    $config = require_once '../src/config.php';
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
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"></script>
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

    <div class="row">
        <h2>Add a developer</h2>

        <form class="form-inline" role="form">
            <div class="form-group">
                <input type="text" placeholder="GitHub Username" class="form-control">
            </div>
            <div class="form-group">
                <input type="text" placeholder="Price" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="glyphicon glyphicon-shopping-cart"></i>
                Add
            </button>
        </form>
    </div>

    <div class="cart row">
        <h2>Cart</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Username</th>
                <th>Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr class="product">
                <td>brenoc</td>
                <td>$224</td>
                <td>
                    <button class="btn btn-danger pull-right">
                        <i class="glyphicon glyphicon-trash"></i>
                        Remove
                    </button>
                </td>
            </tr>
            <tr class="product">
                <td>firstdoit</td>
                <td>$416</td>
                <td>
                    <button class="btn btn-danger pull-right">
                        <i class="glyphicon glyphicon-trash"></i>
                        Remove
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="totalizer row">
        <div class="col-sm-5">
            <div class="row">
                <table class="table">
                    <tbody>
                    <tr class="total">
                        <td>Total</td>
                        <td>$640</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
