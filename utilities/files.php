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
	return user_path($name, $isJs) . SVC;
}

function user_data($name)
{
	return user_path($name) . DATA;
}

function user_index($name, $isJs = false)
{
	return user_path($name, $isJs) . INDEX;
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

function user_quips_flag($name)
{
	return user_path($name) . '/' . QUIPS_FLAG;
}

function user_quips_cache($name)
{
	return user_path($name) . '/' . QUIPS_CACHE;
}