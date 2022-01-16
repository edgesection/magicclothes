<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
	if(((integer) $checkAuth['id']) >= 1){
			
		echo 'ok';
		exit;
			
	}
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$address = $_POST['address'];
	$methodR = $_POST['methodR'];
	
	mysqli_query($connect, "INSERT INTO `users` (`login`,`password`,`first_name`,`last_name`,`address`,`method`) VALUES ('{$login}','{$password}','{$fName}','{$lName}','{$address}','{$methodR}')") or die("nA");
	
	setCookie("login", $login, time()+86400);
	setCookie("password", $password, time()+86400);
	
	echo 'ok';
	
?>