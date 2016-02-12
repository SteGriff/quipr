<?php

require_once 'actions.php';
require_once 'files.php';

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
	$id = dal_player_authenticate_and_get_id($name, $password);
	if ($id === false)
	{
		echo "Fail";
		http_fatal_400('Wrong username or password');
	}
	else
	{
		session_start();
		$_SESSION['userID'] = $id;
		echo $id;
		http_200($id);
	}
}

function user_authenticate($name, $password)
{
	$passwordCrypt = crypt($password);
	
	//Get user data
	$userSvc = user_path($name) . '/svc.php';
	
	
	
}

function user_exists($name)
{
	return is_dir(user_path($name));
}

function update_user_svc($name)
{
	$target = user_path($name) . 'svc.php'
	copy(template_file('svc.php'), $target);
}

function create_user($name, $password)
{
	//Encrypt password
	$passwordCrypt = crypt($password);
	
	//Create user and quips directory
	// user/{name}
	$userDir = user_path($name);
	mkdir($userDir);
	
	// user/{name}/q
	$quipsDir = user_quips_path($name);
	mkdir($quipsDir);
	
	//Copy master svc node
	update_user_svc($name);
	
	//Get master data content 
	$userContent = file_get_contents(template_file('data.php'));
	
	//Replace keys with data
	$userContent = str_replace('{{USER_NAME}}', $name, $userContent);
	$userContent = str_replace('{{USER_PASSWORD}}', $passwordCrypt, $userContent);
	$userContent = str_replace('{{USER_DIR}}', $userDir, $userContent);
	$userContent = str_replace('{{QUIPS_DIR}}', $quipsDir, $userContent);
	
	//Write the user svc file
	file_put_contents("$userDir/data.php", $userContent);
		
	//Use name as uid for now
	// Later lets generate cool base 64 ids
	return $name;
}