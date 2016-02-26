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
		
	case 'follow':
		//The logged-in user follows this person
		follow();
		break;
	
	default:
		if ($action !== null)
		{
			http_fatal_400('No such action');
		}
}

function get_quips()
{
	global $username;

	//When a quip is posted, mark the directory as dirty
	// if the directory is dirty, rebuild a PHP object file cache
	// like the grammar in Paprika.me.uk
	// Then return the PHP object cache
	
	$cacheLocation = user_quips_cache($username);
	$quipCache = [];
	
	if (quips_are_dirty())
	{
		//Build the cache
			
		//Open all quip files and add to array
		$quipFiles = all_quip_files();
		sort($quipFiles);
		
		foreach($quipFiles as $q)
		{
			//Open quip file and separate metadata from quip
			$dataJson = file_get_contents($q);
			
			$data = parse_quip_content($dataJson);
			
			//Add this quip data onto the array, using metadata as a key
			$quipCache[$data->date] = $data->quip;
		}
		
		//Export the PHP object to cache file
		$quipCacheString = '<?php $quipCache = ' . var_export($quipCache, true) . ' ?>';
		
		file_put_contents($cacheLocation, $quipCacheString);
		
		//Remove the dirty flag
		flag_quips_cached();
	}
	else
	{
		//Load the cache file
		$cacheLocation = user_quips_cache($username);
		
		//This will rewrite the value of $quipCache
		include_once $cacheLocation;
	}
	
	//Return the quip data
	return $quipCache;
}

function get_feed()
{
	global $username;
	
	//Get all people we're following (they're symlinks)
	$expr = user_following_path($username) . '/*';
	$following = glob($expr);
	
	$feed = [];
	
	//Ask each user for their quips
	foreach($following as $friend)
	{
		$friend = "$friend/" . QUIPS_CACHE;
		echo "$friend \r\n";
		
		//Import their $quipCache
		include_once $friend;
		
		foreach($quipCache as $k => $v)
		{
			$feed[$k] = $v;
		}
		
	}
	
	var_dump($feed);
	return $feed;
}

function parse_quip_content($dataJson)
{
	return json_decode($dataJson);
}

function post_quip($content)
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
	
	flag_quips_dirty();
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

/* Quip caching and dirty state tracking */

function flag_quips_dirty()
{
	global $username;
	$flag = user_quips_flag($username);
	
	//Create flag file
	touch($flag);
}

function flag_quips_cached()
{
	global $username;
	$flag = user_quips_flag($username);
	
	//Delete flag file
	unlink($flag);
}

function quips_are_dirty()
{
	global $username;
	$flag = user_quips_flag($username);
	return file_exists($flag);
}

/* Following */

function follow()
{
	//The username of this person
	global $username;
	
	$target = $username;
	
	//Check we are authentic
	session_start();
	$follower = $_SESSION['username'];
	echo $follower;
	
	// Get the new follower's fing path
	// '/user/ste/fing'
	$fingPath = user_following_path($follower);
	echo $fingPath;
	
	// Make a name for the new symlink there
	// '/user/ste/fing/jim' (LN)
	$newLink = "$fingPath/$target";
	echo $newLink;
	
	$linkTo = user_path($target);
	echo $linkTo;
	
	//Create a link from '/user/ste/fing/jim' to '/user/jim' (the target)
	symlink($linkTo, $newLink);
	
	echo "Done";
}