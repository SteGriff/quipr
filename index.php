<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>

</style>
<script src="p/script/jq.js"></script>
</head>
<body>
	
	<h1>Welcome</h1>

	<p class="message">
		<?php
			session_start();
			if (isset($_SESSION['userID']))
			{
				header('Location: p/home.php');
			}
			elseif (isset($_SESSION['home-message']))
			{
				echo $_SESSION['home-message'];
				unset($_SESSION['home-message']);
			}
			elseif (isset($_GET['logout']))
			{
				echo "You were succcessfully logged out.";
			}
		?>
	</p>
	
	
	<p>Login/Register</p>
	<form class="login-form" action="#">
		<label>Name <input class="login name" type="text" name="name" placeholder="username"></label>
		<label>Password <input class="login password" type="password" name="pass" placeholder="password"></label>
		<button class="login">Login</button>
		<button class="register">Register</button>
	</form>

<script>
	
	data = null;
	LOGIN = "login";
	REGISTER = "register";
	
	function feedback(message)
	{
		$('.message').text(message);
	}
	
	function submit(e, action){
		e.preventDefault();
		
		//console.log("submit");
		
		data = $('form.login-form').serialize();
		data += "&a=" + action;
		
		//console.log(data);
		
		$.post("controller/users.php", data)
			.done(function(x){
				if (action == LOGIN)
				{
					window.location = 'p/home.php';
				}
				else
				{
					feedback("Account created! Logging you in now, please wait...");
					//Registration successful; log in automatically
					submit(new Event(null), LOGIN);
				}
			})
			.fail(function(x)
			{
				feedback(x.statusText);
			});
	}
			
	$(function(){

		console.log("Ready!");
		
		$('button.login').click(function(e){
			submit(e, LOGIN);
		});
		
		$('button.register').click(function(e){
			submit(e, REGISTER);
		});
		
	});
	
</script>
</body>
</html>