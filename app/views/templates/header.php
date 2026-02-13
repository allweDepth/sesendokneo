<?php
echo $data['awalHeader'];
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AHSP <?= $data['title']?></title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/vendor/node_modules/fomantic-ui/dist/semantic.min.css">
    <link rel="stylesheet" href="<?= BASEURL; ?><?= $data['css'] ?>">
    <?= $data['tambahan_css'] ?>
    <link rel="shortcut icon" href="<?= BASEURL; ?>img/logo.png">
</head>
<body>