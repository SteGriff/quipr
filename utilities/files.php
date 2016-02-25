<?php

require_once 'config.php';

function root($isJs)
{
	return $isJs
		? APP_ROOT
		: ROOT;
}

/*
	Base
*/
function template_file($filename, $isJs = false)
{
	return root($isJs) . USER_STUB . $filename;
}

function user_path($name = '', $isJs = false)
{
	return root($isJs) . USERS . $name;
}

/*
	Consumers of user_path
*/
function user_svc($name, $isJs = false)
{
	return user_path($name, $isJs) . '/svc.php';
}

function user_data($name)
{
	return user_path($name) . '/data.php';
}

function user_index($name, $isJs = false)
{
	return user_path($name, $isJs) . '/index.php';
}

function user_quips_path($name)
{
	return user_path($name) . '/q';
}

function user_following_path($name)
{
	return user_path($name) . '/fing';
}

function user_followers_path($name)
{
	return user_path($name) . '/fers';
}

