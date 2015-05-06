<?php
require ('vendor/autoload.php');

use Goutte\Client;

function getClient()
{
	static $client;
	$client = $client ? $client : new Client();
	return $client;
}

function crawlAll()
{
	$restaurants = array();
	$restaurants[] = crawlOrangeriet()[0];
	$restaurants[] = theFort()[0];
	$restaurants[] = crawlSand()[0];
	$restaurants[] = crawlGladje()[0];
	$restaurants[] = crawlAlpina()[0];

	return $restaurants;
}

function crawlGladje()
{
	// Restaurang Glädje
	// -----------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://www.restauranggladje.se/skovde/');
	$foodbox          = $crawler->filter('aside.sidebar > div')->first();
	$mealTitles       = $foodbox->filter('h3');    // Two headings, normally
	$mealDescriptions = $foodbox->filter('.food'); // Two texts, normally
	$output           = array();

	if(count($mealTitles)) {
		$ix = 0;

		$output['restaurant_name'] = "Restaurang Glädje";

		foreach($mealTitles as $title) {
			$output['meals'][] = array(
				'description' => $title->textContent .', '.$mealDescriptions->eq($ix)->text()
			);

			$ix++;
		}
	}

	return array($output);
}


function crawlOrangeriet()
{
	// Restaurang Orangeriet
	// ---------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://mattiasmat.se/');
	$mealDescriptions = $crawler->filter('#lunch-content-orangeriet h4');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['restaurant_name'] = 'Restaurang Orangeriet';
		$ix = 0;
		$dayofweek = date('N'); // ISO-8601 numeric representation of the day of the week
		$descriptions = explode('<br>', $mealDescriptions->eq($dayofweek - 1)->nextAll()->first()->html());

		foreach ($descriptions as $description) {
			$output['meals'][] = array(
				'description' => $description
			);
		}
	}

	return array($output);
}


function crawlAlpina()
{
	// Restaurang Alpina
	// -----------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://www.restaurangalpina.se/');
	$foodbox          = $crawler->filter('.sidebar .view-lunch')->first();
	$mealDescriptions = $foodbox->filter('.vh-vad, .field-item');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['restaurant_name'] = 'Restaurang Alpina';

		foreach ($mealDescriptions as $mealDescription) {
			$trimmedFood = trim($mealDescription->textContent);
			if (!empty($trimmedFood))
			$output['meals'][] = array(
				'description' => $trimmedFood
			);
		}
	}

	return array($output);
}


function crawlSand()
{
	$client     = getClient();
	$crawler    = $client->request('GET', 'http://sandmeza.se/lunchmeny/');
	$foodbox    = $crawler->filter('.menyblack .one-half')->last();
	$lunchDays  = $foodbox->filter('ul');
	$dayofweek  = date('N'); // ISO-8601 numeric representation of the day of the week
	$lunchDay   = $lunchDays->eq($dayofweek - 1)->children();
	$output['restaurant_name'] = "Restaurang Sand Meza";
	foreach ($lunchDay as $meal) {
		$output['meals'][] = array(
			'description' => $meal->textContent
		);
	}

	return array($output);
}


function theFort()
{
	$output     = array(
		'restaurant_name' => 'Fortet',
		'meals' => array()
		);

	$foodStuffs = array(
		'Fabriksköttbullar med någon form av potatismos',
		'Ostburgare utan ost, med ägg.',
		'Sås?',
		'Köttinspirerad bit med fyrstorkad persiljeklump, serveras på en bädd av rotfruktsskav',
		'Gyros à la Svansen',
	);
	$usedFood    = array();

	// Select 2 at random
	while (count($usedFood) <= 1) {
		$tryCurrent = $foodStuffs[array_rand($foodStuffs)];
		if (!in_array($tryCurrent, $usedFood)) {
			$usedFood[] = $tryCurrent;
			$output['meals'][] = array(
				'description' => $tryCurrent
				);
		}
	}

	return array($output);
}