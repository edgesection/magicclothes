<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
	if(((integer) $checkAuth['id']) >= 1){
			
		$fName = $_POST['fName'];
		$lName = $_POST['lName'];
		$address = $_POST['address'];
		
		mysqli_query($connect, "UPDATE `users` SET `first_name` = '{$fName}', `last_name` = '{$lName}', `address` = '{$address}' WHERE `id` = ".$checkAuth['id']."") or die("Ошибка обновления данных");
		
		echo 'ok';
			
	}
	
?>