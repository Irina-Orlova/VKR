<?php
require "session_start.php";
require_once "connect.php";
require "blocks/header.php";?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
        <title>Авторизация</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/Main.css">
		<script src="js/jquery-3.4.1.js"></script>
			
		
    </head>
    <body>
<?php 

$conn = OpenConnection(); 
echo "<hr><hr> Просмотр";


require "blocks/footer.php";
?>