<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 4/22/2019
 * Time: 8:15 PM
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index View</title>
</head>
<body>
<?php var_dump($data['name'])?>
    <?php for($i=0;$i<count($data['id']);$i++): ?>
        <h1><?php echo $data['id'][$i] ?></h1>
    <?php endfor; ?>
<!--    <h1>This is my view</h1>-->

