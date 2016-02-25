<?php
	session_start();
	if (empty($_SESSION['userID']))
	{
		$_SESSION['home-message'] = 'Please log in';
		header('Location: ../index.php');
	}
	
	require_once '../utilities/files.php';
	
	$username = $_SESSION['username'];
	$userSvc = user_svc($username, true);
?>
<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>

</style>
</head>
<body>
	
	<?php include 'bits/topbar.php'; ?>
	
	<h1>Home</h1>

	<p class="message"></p>
	
	<form class="post-form">
		<p>What's up?</p>
		<textarea name="qc" class="post fix-width"></textarea>
		<button class="clickable post-button">Post</button>
	</form>

	<p>Stream:</p>
	<div class="stream">
	</div>
	
	<p>My quips:</p>
	<div class="my-quips">
	</div>
	
<script src="script/jq.js"></script>
<script>
	
	url = "<?=$userSvc?>";
	data = null;
	POST = "post";

	function feedback(message)
	{
		$('.message').text(message);
	}
	
	function submit(e, action){
		e.preventDefault();
		
		data = $('form.post-form').serialize();
		data += "&a=" + action;
				
		$.post(url, data)
			.done(function(x){
				feedback("Posted!");
			})
			.fail(function(x)
			{
				feedback("Failed to post: " + x.statusText);
			});
	}
			
	$(function(){

		$('button.post-button').click(function(e){
			submit(e, POST);
		});
		
	});
	
</script>
</body>
</html>