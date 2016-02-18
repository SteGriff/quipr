<?php

require_once '../../utilities/actions.php';
require_once '../../utilities/files.php';
require_once 'data.php';

switch ($action)
{
	case 'post':
		$content = from_post('qc');
		post_quip($content);
		break;
	
	case 'get':
		$quips = get_quips();
		echo json_encode($quips);
		break;
	
	default:
		if ($action !== null)
		{
			http_fatal_400('No such action');
		}
}

function authenticate($password)
{
	
	password_verify();
}

function get_quips()
{
	//TODO optimize me
	
	//When a quip is posted, mark the directory as dirty
	// if the directory is dirty, rebuild a PHP object file cache
	// like the grammar in Parika.me.uk
	// Then return the PHP object cache
	
	//For now, open all quips files and add to array
	$quipFiles = all_quip_files();
	sort($quipFiles);
	
	$quipsData = [];
	foreach($quipFiles as $q)
	{
		//Open quip file and separate metadata from quip
		$dataJson = file_get_contents($q);
		
		$data = parse_quip_content($dataJson);
		
		//Add this quip data onto the array, using metadata as a key
		$quipsData[$data->date] = $data->quip;
	}
	
	//Return the array of quip strings
	return $quipsData;
}

function parse_quip_content($dataJson)
{
	return json_decode($dataJson);
}

function write_quip_file($content)
{
	global $username; 
	
	$date = date("o-m-d\TH:i:s\Z");
	
	//Build file data JSON
	$data = [
		'date' => $date,
		'quip' => $content
	];
	
	$dataJson = json_encode($data);
	
	//Write to new quip file
	$quipsDir = user_quips_path($username);
	$quipFile = "$quipsDir/" . next_quip_file();
	file_put_contents($quipFile, $dataJson);
}

function post_quip($content)
{
	//Check logged in via session
	session_start();
	
	//Sanitize content?
	
	//Write file
	write_quip_file($content);
	
}



function next_quip_file()
{
	return quip_count() + 1 . QUIP_EXT;
}

function quip_count()
{
	return count(all_quip_files());
}

function all_quip_files()
{
	global $username;
	
	$expr = user_quips_path($username) . '/*' . QUIP_EXT;
	$quips = glob($expr);
	
	return $quips;
}