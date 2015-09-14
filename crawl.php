<?php
require ('vendor/autoload.php');

use Goutte\Client;

function getClient()
{
	static $client;
	$client = $client ? $client : new Client();
	return $client;
}