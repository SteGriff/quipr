<?php

function template_file($filename)
{
	return "user-template/$filename";
}

function user_path($name)
{
	return "users/$name";
}

function user_data($name)
{
	return user_path($name) . '/data.php';
}

function user_svc($name)
{
	return user_path($name) . '/svc.php';
}

function user_quips_path($name)
{
	return "users/$name/q";
}

function user_svc($name)
{
	
}