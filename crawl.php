<?php
require ('vendor/autoload.php');

use Goutte\Client;

function getClient()
{
	static $client;
	$client = $client ? $client : new Client();
	return $client;
}

function crawl()
{
	$restaurants = mb_split(',', RESTAURANTS);
	$restaurants = array_unique($restaurants);

	$returnValue = array();

	foreach ($restaurants as $restaurant) {
		$result = loadResturant($restaurant);
		if ($result) {
			$returnValue[] = $result;
		}
	}

	return $returnValue;
}


function loadResturant($restaurant)
{
	$pieces = mb_split('/', $restaurant);
	$file = 'restaurants/'. mb_convert_case(implode('/', $pieces), MB_CASE_LOWER, 'UTF-8').'.php';
	$function = mb_convert_case(implode('_', $pieces), MB_CASE_LOWER, 'UTF-8');

	if (file_exists($file))
	{
		require_once $file;
		if (function_exists($function))
		{
			return call_user_func($function);
		}
	}

	return false;
}