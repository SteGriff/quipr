<?php
	require_once 'data.php';
?>
<!DOCTYPE HTML>
<html>
<head>
<title><?=$username?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>

</style>
</head>
<body>
	
	<?php include '../../p/bits/topbar.php'; ?>
	
	<h1><?=$username?> on quipr</h1>

	<button class="follow">Follow</button>
	<p class="message"></p>
	
	<p>Quips:</p>
	<div class="quips">
	Loading...
	</div>
	
<script src="../../p/script/jq.js"></script>
<script>
		
	function feedback(message)
	{
		$('.message').text(message);
	}
	
	$(function(){

		$('.quips').load('quips-view.php');
		
		$('.follow').click(function(){
			
			var data = {'a' : 'follow'};
			
			$.post('svc.php', data)
				.done(function(){
					feedback("You're now following this user");
				})
				.fail(function(){
					feedback("Something went wrong");
				});
				
		});
		
	});
	
</script>
</body>
</html>