<?php

require_once 'svc.php';
$quips = get_quips();

?>

<?php foreach($quips as $k => $q): ?>

	<div class="quip">
		<time><?=date_format(date_create($k), "d m o, H:i")?></time>
		<div class="quip-content">
			<?=$q?>
		</div>
	</div>
	
<?php endforeach; ?>