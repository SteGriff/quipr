<?php

require_once '../files.php';

$username = "{{USER_NAME}}";
$password = "{{USER_PASSWORD}}";
$userDir = "{{USER_DIR}}";
$quipsDir = "{{QUIPS_DIR}}";

function get_quips()
{
	
}

function post_quip($content)
{
	//Sanitize content?
	
	//Write metadata
	// just date for now
	$metadataLine = date("o-m-d\Th:i:s\Z");
	
	$content = "$metadataLine\n$content";
	
	//Write to new quip file
	$quipFile = "$quipsDir/" . next_quip_file();
	file_put_contents($quipFile, $content);
	
}

function next_quip_file()
{
	return quip_count() + 1 . '.txt';
}

function quip_count()
{
	$qps = glob('./*.php');
	echo count($qps);
}