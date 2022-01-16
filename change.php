
<?php

	$connect = mysqli_connect("localhost", "root", "root", "internet_shop") or die("Ошибка соединения");
	
	$login = $_COOKIE['login'];
	$password = $_COOKIE['password'];
	
	$checkAuth = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '{$login}' AND `password` = '{$password}'"));
	
?>

<!DOCTYPE html>
<html>

<head>
	<title>Настройки MC</title>
	<script src="jquery.js"></script>
	
	<link rel="shortcut icon" href="favicon.png">
	<link rel="stylesheet" href="change.css">
	
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
			
				<span id="name">Изменить данные профиля:</span>
			
				<div class="change">
				
					<input type="text" placeholder="Введите имя" value="'.$checkAuth['first_name'].'" id="fName">
					<input type="text" placeholder="Введите фамилию" value="'.$checkAuth['last_name'].'" id="lName">
					<input type="text" placeholder="Введите адрес" value="'.$checkAuth['address'].'" id="address">
					<div class="submit">Сохранить</div>
				
				</div>
			
			';
			
		}else{
			
			echo '<script> location.href = "index.php"; </script>';
			
		}
	
		?>
	
	</main>

</body>

<script>

	window.onload = function(){
		
		let access = false;
		
		$("main div.change div.submit").click(function(){
			
			if(access == true){
				
				return false;
				
			}
			
			let fName = $("main div.change input#fName").val();
			let lName = $("main div.change input#lName").val();
			let address = $("main div.change input#address").val();
			
			$.ajax({
				url: "changeData.php",
				method: "POST",
				data: {
					fName: fName,
					lName: lName,
					address: address
				},
				success: function(data){
					
					if(data == "ok"){
						
						$("main div.change div.submit").text("Данные изменены");
						$("main div.change div.submit").css({"background": "initial", "color": "black"});
						access = true;
						
						setTimeout(function(){
							
							location.href = "profile.php";
							
						}, 3000);
						
					}
					
				}
			});
			
		});
		
	}

</script>

</html>