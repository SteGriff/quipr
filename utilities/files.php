<?php

/*
root of the application
should have a closing slash /
*/
const ROOT = '/updog/';


function template_file($filename)
{
	return ROOT . "user-template/$filename";
}

function user_path($name)
{
	return ROOT . "users/$name";
}

function user_data($name)
{
	return ROOT . user_path($name) . '/data.php';
}

function user_svc($name)
{
	return ROOT . user_path($name) . '/svc.php';
}


function user_quips_path($name)
{
	return ROOT . "users/$name/q";
}
