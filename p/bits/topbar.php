<?php
if (!isset($_SESSION))
{
	session_start();
}

$loggedIn = false;
if (isset($_SESSION['username']))
{
	$loggedIn = $_SESSION['username'];
	$userSvc = user_svc($username, true);	
}

?>

<header>
	<? if($loggedIn !== false){ ?>
	
		<p>Logged in as <?=$loggedIn?></p>
		<button class="logout-button">Logout</button>
		
	<? } else { ?>
	
		<p><a href="#">Log in or sign up</a></p>
		
	<? } ?>
</header>

<script>
$(function(){

	$('.logout-button').click(function(){
							
		root = "<?=root(true)?>";
		url = root + "/controller/users.php";
		loggedOut = root + "?logout=true";
		
		data = {'a' : 'logout'};
		
		$.post(url, data)
			.done(function(x){
				console.log(x);
				window.location = loggedOut;
			})
			.fail(function(x)
			{
				window.location = root;
			});
			
	});
});
</script>