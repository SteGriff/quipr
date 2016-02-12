<?php

header("content-type: text/plain;");

echo date("o-m-d\Th:i:s\Z"). "\n";
//echo date('c');

$x = glob('./*.php');
echo count($x);
foreach ($x as $f)
{
	echo "$f\n";
}
?>