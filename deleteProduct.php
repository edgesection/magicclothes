<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
	if(((integer) $checkAuth['id']) >= 1){
		
		$idProduct = $_POST['idProduct'];
		
		$checkProduct = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `products` WHERE `id` = {$idProduct}"));
		
		if($checkProduct['idSeller'] == $checkAuth['id']){
			
			if($checkProduct['del'] == 0){
				mysqli_query($connect, "UPDATE `products` SET `del` = 1 WHERE `id` = {$idProduct}") or die("Ошибка удаления товара");
			
				echo 'ok_1';
			}else{
				mysqli_query($connect, "UPDATE `products` SET `del` = 0 WHERE `id` = {$idProduct}") or die("Ошибка восстановления товара");
			
				echo 'ok_2';
			}
			
		}
		
	}
	
?>