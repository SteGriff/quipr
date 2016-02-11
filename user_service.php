<?php

require_once 'actions.php';

switch ($action)
{
	case 'register':
		$name = from_post('name');
		$pass = from_post('pass');
		register($name, $pass);
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
		return "Name taken";
	}
	else
	{
		create_user($name, $pass);
	}
	
}

function user_path($name)
{
	return "users/$name";
}

function user_exists($name)
{
	return is_dir(user_path($name));
}

function create_user($name, $password)
{
	$userDir = user_path($name);
	mkdir($userDir);
	copy('master.php', "$userDir/svc.php");
}