<?php

require_once '../utilities/actions.php';
require_once '../utilities/files.php';

switch ($action)
{
	case 'register':
		$name = from_post('name');
		$pass = from_post('pass');
		register($name, $pass);
		break;
	
	case 'login':
		$name = from_post('name');
		$pass = from_post('pass');
		login($name, $pass);
		break;
		
	case 'logout':
		logout();
		break;
		
	case 'update':
		update_users();
		echo "Done!";
		break;
	
	default:
		if ($action !== null)
		{
			http_fatal_400('No such action');
		}
}

function register($name, $pass)
{
	if (user_exists($name))
	{
		http_fatal_400('Name taken');
	}
	else
	{
		//Just using name as uid for now
		$uid = create_user($name, $pass);
		http_201('user', $uid);
	}
	
}

function login($name, $password)
{
	if (user_authenticate($name, $password))
	{
		//TODO generate access token
		session_start();
		$_SESSION['userID'] = $name;
		$_SESSION['username'] = $name;
		echo $name;
		http_200($name);
	}
	else
	{
		$error = 'Wrong username or password';
		echo $error;
		http_fatal_400($error);
	}
}

function logout()
{
	if (!isset($_SESSION))
	{
		session_start();
	}
	
	unset($_SESSION['userID']);
	unset($_SESSION['username']);
	
	session_destroy();
}

function user_authenticate($name, $password)
{
	//Get user data
	$userDataFile = user_data($name);
	
	//Maybe this will suck
	include_once $userDataFile;
	
	$success = password_verify($password, $pcrypt);
	return $success;
}

function user_exists($name)
{
	return is_dir(user_path($name));
}

function update_user_files($name)
{
	copy(template_file('svc.php'), user_svc($name));
	copy(template_file('index.php'), user_index($name));
	copy(template_file('quips-view.php'), user_path($name) . '/quips-view.php');
	copy(template_file('feed-view.php'), user_path($name) . '/feed-view.php');
	
	//Force quip cache rebuild
	touch(user_quips_flag($name));
}

function create_user($name, $password)
{
	//Encrypt password
	$passwordCrypt = password_hash($password, PASSWORD_DEFAULT);
	
	$old = umask(0);
	
	//Create user and quips directory (suppress warnings with @)
	// user/{name}
	$userDir = user_path($name);
	mkdir($userDir, 0777, true);
	
	// user/{name}/q 
	$quipsDir = user_quips_path($name);
	mkdir($quipsDir, 0777, true);
	
	// user/{name}/fing and fers
	mkdir(user_following_path($name), 0777, true);
	mkdir(user_followers_path($name), 0777, true);
	
	//Copy stub files to user
	update_user_files($name);
	
	//Build initial empty quip cache
	$quipCacheStub = '<?php $quipCache = []; ?>';
	file_put_contents(user_quips_cache($name), $quipCacheStub);
	
	//Get master data content 
	$userContent = file_get_contents(template_file('data.php'));
	
	//Replace keys with data
	$userContent = str_replace('{{USER_NAME}}', $name, $userContent);
	$userContent = str_replace('{{USER_PASSWORD}}', $passwordCrypt, $userContent);

	//Write the user svc file
	file_put_contents("$userDir/data.php", $userContent);
		
	umask($old);
	
	//Use name as uid for now
	// Later lets generate cool base 64 ids
	return $name;
}

function update_users()
{
	$expr = user_path() . '*';
	$users = glob($expr);
	
	$ct = 0;
	
	foreach($users as $u)
	{
		$u = basename($u);
		update_user_files($u);
		
		$ct += 1;
	}
	
	echo "Processed $ct users.\r\n";
	
}
