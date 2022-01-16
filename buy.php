<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
	if(((integer) $checkAuth['id']) >= 1){
			
		$idProduct = $_POST['idProduct'];
		
		if(((integer) $idProduct) >= 1 AND $idProduct !== "" AND $idProduct !== NULL){
			
			mysqli_query($connect, "INSERT INTO `orders` (`idProduct`, `idBuyer`, `time`) VALUES ({$idProduct}, ".$checkAuth['id'].", ".time().")") or die('Ошибка покупки товара');
			
			echo 'ok';
			
		}
		
	}

?>