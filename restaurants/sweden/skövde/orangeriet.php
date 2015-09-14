<?php

function sweden_skövde_orangeriet()
{
	// Restaurang Orangeriet
	// ---------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://mattiasmat.se/');
	$mealDescriptions = $crawler->filter('#lunch-content-orangeriet h4');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['name'] = 'Orangeriet';
		$ix = 0;
		$dayofweek = date('N'); // ISO-8601 numeric representation of the day of the week
		$descriptions = explode('<br>', $mealDescriptions->eq($dayofweek - 1)->nextAll()->first()->html());

		foreach ($descriptions as $description) {
			$output['meals'][] = array(
				'description' => $description
			);
		}
	}

	return $output;
}