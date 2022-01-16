<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
	if(((integer) $checkAuth['id']) >= 1){
			
		if($checkAuth['method'] == "2"){
			
			$nameP = $_POST['nameP'];
			$priceP = $_POST['priceP'];
			
			mysqli_query($connect, "INSERT INTO `products` (`name`, `price`, `idSeller`, `del`) VALUES ('{$nameP}', {$priceP}, ".$checkAuth['id'].", 0)") or die("Ошибка добавления товара");
			
			echo 'ok';
			
		}
			
	}
	
?>