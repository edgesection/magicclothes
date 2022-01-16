
<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
?>

<!DOCTYPE html>
<html>

<head>
	<title>Добавления товара MC</title>
	<script src="jquery.js"></script>
	
	<link rel="shortcut icon" href="favicon.png">
	<link rel="stylesheet" href="addProduct.css">
	
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
			
			echo '
				
				<span>Добавление товара:</span>
			
				<div class="addProduct">
					<input type="text" placeholder="Название товара" id="nameProduct">
					<input type="text" placeholder="Цена товара" id="priceProduct">
					<div class="submit">Добавить товар</div>
				</div>
			
			';
			
		}else{
			
			echo '<script> location.href = "index.php"; </script>';
			
		}
	
		?>
	
	</main>

<script>

	window.onload = function(){
		
		let access = false;
		
		$("main div.addProduct div.submit").click(function(){
			
			if(access == true){
				
				return false;
				
			}
			
			let nameProduct = $("main div.addProduct input#nameProduct").val();
			let priceProduct = $("main div.addProduct input#priceProduct").val();
			
			$.ajax({
				url: "createProduct.php",
				method: "POST",
				data: {
					nameP: nameProduct,
					priceP: priceProduct
				},
				success: function(data){
					
					if(data == "ok"){
						
						access = true;
						
						$("main div.addProduct div.submit").css({"background": "initial", "color": "black"});
						$("main div.addProduct div.submit").text("Товар добавлен");
						
						setTimeout(function(){
							
							location.href = "profile.php";
							
						}, 3000);
						
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