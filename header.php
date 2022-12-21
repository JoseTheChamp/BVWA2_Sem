<?php
session_start();
?>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>WhatPad</title>
      <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div class="menu-box">
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php
            if (isset($_SESSION["userId"])){
                echo "<li><a href='editor.php'>Editor</a></li>";
                echo "<li><a href='mygallery.php'>My Gallery</a></li>";
                echo "<li><a href='gallery.php'>Gallery</a></li>";
                echo "<li><a href='includes/logout.inc.php'>Log Out</a></li>";
                $txt = $_SESSION['userUid'];
                echo "<li><p>UserName: $txt</p></li>";
            }else{
                echo "  <li><a href='gallery.php'>Gallery</a></li>
                        <li><a href='login.php'>Log In</a></li>
                        <li><a href='signup.php'>Sign In</a></li>";
            }
            ?>
        </ul>
    </div>