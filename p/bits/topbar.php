<?php
if (!isset($_SESSION))
{
	session_start();
}
$loggedin = $_SESSION['username'];
?>

<header>

	<p>Logged in as <?=$loggedin?></p>

</header>