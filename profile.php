
<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
?>

<!DOCTYPE html>
<html>

<head>
	<title>Профиль MC</title>
	<script src="jquery.js"></script>
	
	<link rel="shortcut icon" href="favicon.png">
	<link rel="stylesheet" href="profile.css">
	
</head>

<body>

	<header>
		<a href="index.php"><div class="logo">
			<img src="favicon.png">
			<span>MagicClothes</span>
		</div></a>
	</header>
	
	<main>
	
		<?php
	
		if(((integer) $checkAuth['id']) >= 1){
			
			$idProfile = $_GET['id'];
			
			if($idProfile == "" OR $idProfile == 0 OR $idProfile == NULL){
				$idProfile = $checkAuth['id'];
			}
			
			$dataProfile = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = {$idProfile}"));
			
			$typeAccount = array(1 => "Покупатель", 2 => "Продавец");
			
			echo '
			
				<div class="dataProfile">
				
					<span>Имя: <b>'.$dataProfile['first_name'].'</b></span>
					<span>Фамилия: <b>'.$dataProfile['last_name'].'</b></span>
					<span>Адрес: <b>'.$dataProfile['address'].'</b></span>
					<span>Тип аккаунта: <b>'.$typeAccount[$dataProfile['method']].'</b></span>
				
				</div>
			
			';
			
			if($checkAuth['id'] == $dataProfile['id']){
				
				echo '<a href="change.php"><div class="changeDataUser">Изменить данные</div></a>';
				
				if($checkAuth['method'] == "2"){
					
					echo '<a href="addProduct.php"><div class="addProduct">Добавить товар</div></a>';
					
					$myProducts = mysqli_query($connect, "SELECT * FROM `products` WHERE `idSeller` = ".$checkAuth['id']."");
					
					$statusProduct = array("продаётся", "удалён");
					$actButton = array("Удалить товар", "Продавать товар");
					
					while($myProduct = mysqli_fetch_assoc($myProducts)){
						
						echo '<div class="myProduct myProduct'.$myProduct['id'].'" id="'.$myProduct['id'].'">
						
							<span id="name">Название: <b>'.$myProduct['name'].'</b></span>
							<span id="price">Цена: <b>'.$myProduct['price'].'</b></span>
							<span id="status">Статус: <b>'.$statusProduct[$myProduct['del']].'</b></span>
						
							<div class="deleteProduct" id="'.$myProduct['id'].'">'.$actButton[$myProduct['del']].'</div>
						
						</div>';
						
					}
					
					goto isSeller;
					
				}
				
				echo '<div class="orders"><span id="name">Заказы:</span>';
				
				$orders = mysqli_query($connect, "SELECT * FROM `orders` WHERE `idBuyer` = {$idProfile}");
				
				while($order = mysqli_fetch_assoc($orders)){
					
					/*if(((integer) $order['count']) <= 0){
						
						echo '<span id="notOrders">Нет покупок</span>';
						break;
						
					}*/
					
					$infoProduct = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `products` WHERE `id` = ".$order['idProduct']."")) or die("Ошибка получения информации о продукте [".$order['id']."]");
					$infoSeller = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = ".$infoProduct['idSeller']."")) or die("Ошибка получения информации о продавце");
					
					echo '<div class="order">
					
						<span>Название: <b>'.$infoProduct['name'].'</b></span>
						<span>Цена: <b>'.$infoProduct['price'].'</b></span>
						<span>Продавец: <a href="profile.php?id='.$infoSeller['id'].'"><b>'.$infoSeller['first_name'].' '.$infoSeller['last_name'].'</b></a></span>
						<span>Дата покупки: <b>'.date("d.m.Y", $order['time']).'</b></span>
					
					</div>';
					
				}
				
				echo '</div>';
				
			}else{
				
				if($checkAuth['method'] == "2"){
					
					echo '<div class="orders"><span id="name">Заказы: </span>';
				
					$orders = mysqli_query($connect, "SELECT * FROM `orders` WHERE `idBuyer` = ".$_GET['id']."");
					
					while($order = mysqli_fetch_assoc($orders)){
						
						$dataProduct = mysqlI_fetch_assoc(mysqli_query($connect, "SELECT * FROM `products` WHERE `id` = ".$order['idProduct'].""));
						$dataSeller = mysqlI_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `id` = ".$dataProduct['idSeller'].""));
						
						echo '<div class="order">
						
							<span>Название: <b>'.$dataProduct['name'].'</b></span>
							<span>Цена: <b>'.$dataProduct['price'].'</b></span>
							<span>Продавец: <a href="profile.php?id='.$dataProduct['idSeller'].'"><b>'.$dataSeller['first_name'].' '.$dataSeller['last_name'].'</b></a></span>
						
						</div>';
						
					}
					
					echo '</div>';
					
					goto isSeller;
					
				}
				
				echo '<div class="orders"><span id="name">Товары: </span>';
				
				$orders = mysqli_query($connect, "SELECT * FROM `products` WHERE `idSeller` = {$idProfile} AND `del` = 0");
				
				while($order = mysqli_fetch_assoc($orders)){
					
					echo '<div class="order">
					
						<span>Название: <b>'.$order['name'].'</b></span>
						<span>Цена: <b>'.$order['price'].'</b></span>
						<div class="submit" id="'.$order['id'].'">Купить</div>
					
					</div>';
					
				}
				
				echo '</div>';
				
			}
			
			isSeller:
			
		}else{
			
			echo '<script> location.href = "index.php"; </script>';
			
		}
	
		?>
	
	</main>
	
	<div class="answer">Товар куплен</div>

<script>

	window.onload = function(){
		
		let idProduct;
		
		$("main div.orders div.order div.submit").click(function(){
			
			idProduct = $(this).attr("id");
			
			$.ajax({
				url: "buy.php",
				method: "POST",
				data: {
					idProduct: idProduct
				},
				success: function(data){
					
					$("div.answer").text("Товар куплен");
					$("div.answer").css({"display": "block"});
					
					setTimeout(function(){
						
						$("div.answer").css({"display": "none"});
						
					}, 3000);
					
				}
			})
			
		});
		
		$("body").on("click", "main div.myProduct div.deleteProduct",function(){

			idProduct = $(this).attr("id");
			
			$.ajax({
				url: "deleteProduct.php",
				method: "POST",
				data: {
					idProduct: idProduct
				},
				success: function(data){
					
					if(data == "ok_1"){
						$("main div.myProduct" + idProduct + " span#status b").text('удалён');
						$("main div.myProduct" + idProduct + " div.deleteProduct").text("Продавать товар");
					}else if(data == "ok_2"){
						$("main div.myProduct" + idProduct + " span#status b").text('продаётся');
						$("main div.myProduct" + idProduct + " div.deleteProduct").text("Удалить товар");
					}else{
						console.log(data);
					}
					
				}
			});
			
		});
		
	}

</script>

</body>

</html>