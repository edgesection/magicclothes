
<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
?>

<!DOCTYPE html>
<html>

<head>
	<title>Магазин MC</title>
	<script src="jquery.js"></script>
	
	<link rel="shortcut icon" href="favicon.png">
	<link rel="stylesheet" href="style.css">
	
</head>

<body>

	<header>
		<a href="profile.php"><div class="logo">
			<img src="favicon.png">
			<span>MagicClothes</span>
		</div></a>
	</header>
	
	<main>
	
		<?php
	
		if(((integer) $checkAuth['id']) >= 1){
			
			echo '<div class="products">';
			
			if($checkAuth['method'] == "2"){
				
				echo 'Заказы: ';
				
				$products = mysqli_query($connect, "SELECT * FROM `products` WHERE `idSeller` = ".$checkAuth['id']."");
			
				while($product = mysqli_fetch_assoc($products)){
					
					$orders = mysqli_query($connect, "SELECT * FROM `orders` WHERE `idProduct` = ".$product['id']."");
					
					while($order = mysqli_fetch_assoc($orders)){
						
						$dataBuyer = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = ".$order['idBuyer'].""));
					
						echo '<div class="product">
							<span id="name">Название: <b>'.$product['name'].'</b></span>
							<span id="price">Цена: <b>'.$product['price'].'</b></span>
							<span id="timeBuy">Время покупки: <b>'.date("d.m.Y", $order['time']).'</b></span>
							<span id="seller">Покупатель: <a href="profile.php?id='.$order['idBuyer'].'">'.$dataBuyer['first_name'].' '.$dataBuyer['last_name'].'</a></span>
						</div>';
						
					}
					
				}
				
				goto isSeller;
				
			}
			
			$products = mysqli_query($connect, "SELECT * FROM `products` WHERE `del` = 0");
			
			echo 'Товары: ';
			
			while($product = mysqli_fetch_assoc($products)){
				
				$dataSeller = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = ".$product['idSeller'].""));
				
				echo '<div class="product">
					<span id="name">Название: <b>'.$product['name'].'</b></span>
					<span id="price">Цена: <b>'.$product['price'].'</b></span>
					<span id="seller">Продавец: <a href="profile.php?id='.$product['idSeller'].'">'.$dataSeller['first_name'].' '.$dataSeller['last_name'].'</a></span>
					<div class="buy" ip="'.$product['id'].'">Купить</div>
				</div>';
				
			}
			
			isSeller:
			
			echo '</div>';
			
		}else{
			
			echo '
			
				<span id="name">Авторизация:</span>
	
				<div class="auth">
					<input type="text" placeholder="Введите логин" id="login">
					<input type="text" placeholder="Введите пароль" id="password">
					<div class="submit">Войти</div>
				</div>
				
				<div class="register">
					<input type="text" placeholder="Введите логин" id="loginR">
					<input type="text" placeholder="Введите пароль" id="passwordR">
					<input type="text" placeholder="Введите имя" id="fNameR">
					<input type="text" placeholder="Введите фамилию" id="lNameR">
					<input type="text" placeholder="Введите адрес" id="addressR">
					<select id="methodR">
						<option selected value="1">Покупатель</option>
						<option value="2">Продавец</option>
					</select>
					<div class="submitR">Зарегистрироваться</div>
				</div>
				
				<div class="reg">Регистрация</div>
				
			';
			
		}
	
		?>
	
	</main>
	
	<div class="answer">Товар куплен</div>

</body>

<script>

	window.onload = function(){
		
		let notAccess = false;
		
		$("main div.auth div.submit").click(function(){
			
			if(notAccess == true){
				return false;
			}
			
			let login = $("main div.auth input#login").val();
			let password = $("main div.auth input#password").val();
			
			$.ajax({
				url: "auth.php",
				method: "POST",
				data: {
					login: login,
					password: password
				},
				success: function(data){
					
					if(data == "ok"){
						location.href = "index.php";
					}else if(data == "nA"){
						
						notAccess = true;
						
						$("main div.auth div.submit").css({"background": "initial", "color": "black"});
						$("main div.auth div.submit").text("Неверные данные");
						
						setTimeout(function(){
							
							$("main div.auth div.submit").css({"background": "steelblue", "color": "white"});
							$("main div.auth div.submit").text("Войти");
							
							notAccess = false;
							
						}, 3000);
						
					}
					
				}
			});
			
		});
		
		$("main div.reg").click(function(){
			
			$("main span#name").text("Регистрация:");
			$("main div.auth").css({"display": "none"});
			$("main div.register").css({"display": "block"});
			$(this).css({"display": "none"});
			
		});
		
		$("main div.register div.submitR").click(function(){
			
			if(notAccess == true){
				return false;
			}
			
			let login = $("main div.register input#loginR").val();
			let password = $("main div.register input#passwordR").val();
			let fName = $("main div.register input#fNameR").val();
			let lName = $("main div.register input#lNameR").val();
			let address = $("main div.register input#addressR").val();
			let methodR = $("main div.register select#methodR").val();
			
			$.ajax({
				url: "reg.php",
				method: "POST",
				data: {
					login: login,
					password: password,
					fName: fName,
					lName: lName,
					address: address,
					methodR: methodR 
				},
				success: function(data){
					
					if(data == "ok"){
						location.href = "index.php";
					}else if(data == "nA"){
						
						notAccess = true;
						
						$("main div.register div.submitR").css({"background": "initial", "color": "black"});
						$("main div.register div.submitR").text("Неверные данные");
						
						setTimeout(function(){
							
							$("main div.register div.submitR").css({"background": "steelblue", "color": "white"});
							$("main div.register div.submitR").text("Зарегистрироваться");
							
							notAccess = false;
							
						}, 3000);
					
					}
				}
			});
			
		});
		
		$("main div.products div.product div.buy").click(function(){
			
			let idProduct = $(this).attr("ip");
			
			$.ajax({
				url: "buy.php",
				method: "POST",
				data: {
					idProduct: idProduct
				},
				success: function(data){
					
					console.log(data);
					
					$("div.answer").text("Товар куплен");
					$("div.answer").css({"display": "block"});
					
					setTimeout(function(){
						
						$("div.answer").css({"display": "none"});
						
					}, 3000);
					
				}
			});
			
		});
		
	}

</script>

</html>