<?php

require_once 'svc.php';
$quips = get_feed();

?>

<?php foreach($quips as $k => $vals): ?>

	<div class="quip">
		<div role="author" class="author">
			<?=$vals['name']?>
		</div>
		<time>
			<?=date_format(date_create($k), "H:i d/m/o")?>
		</time>
		<div class="quip-content">
			<?=$vals['quip']?>
		</div>
	</div>
	
<?php endforeach; ?>