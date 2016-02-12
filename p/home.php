<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<style>

</style>
</head>
<body>
	
	<h1>Home</h1>

	<p class="message"></p>
	
	<form class="post-form">
		<p>What's up?</p>
		<textarea class="post fix-width"></textarea>
		<button class="clickable post-button">Post</button>
	</form>

	<p>Stream:</p>
	

<script src="script/jq.js"></script>
<script>
	
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
		
		$.post("user_service.php", data)
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