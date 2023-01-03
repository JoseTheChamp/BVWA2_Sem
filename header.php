<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WhatPad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stylesChosen.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="menu-box">
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php
        if (isset($_SESSION["userId"])) {
            echo "<li><a href='editor.php'>Editor</a></li>";
            echo "<li><a href='mygallery.php'>My Gallery</a></li>";
            echo "<li><a href='gallery.php'>Gallery</a></li>";
            echo "<li><a href='includes/logout.inc.php'>Log Out</a></li>";
            $txt = $_SESSION['userUid'];
            echo "<li><p>Logged In: $txt</p></li>";
        } else {
            echo "  <li><a href='gallery.php'>Gallery</a></li>
                        <li><a href='login.php'>Log In</a></li>
                        <li><a href='signup.php'>Sign In</a></li>";
        }
        ?>
    </ul>
</div>
<div class="container">