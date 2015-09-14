<?php

function sweden_skövde_vallegrillen()
{
	// Vallegrillen
	// ---------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://vallegrillen.se/vallegrillen-menu/menu.php');
	$mealDescriptions = $crawler->filter('article');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['name'] = 'Vallegrillen';
		$ix = 0;
		$dayofweek = date('N'); // ISO-8601 numeric representation of the day of the week
		$descriptions = $mealDescriptions->eq($dayofweek - 1)->filter('li');

		foreach ($descriptions as $description) {
			$output['meals'][] = array(
				'description' => $description->textContent
			);
		}
	}

	return $output;
}