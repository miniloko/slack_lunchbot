<?php

function sweden_skövde_götasalen()
{
	// Göta salen
	// ---------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'https://eurest.mashie.eu/public/menu/restaurang+g%C3%B6tasalen/b138d3bd');
	$mealDescriptions = $crawler->filter('#week-content')->eq(0)->filter('.container-fluid');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['name'] = 'Göta salen';
		$ix = 0;
		$dayofweek = date('N'); // ISO-8601 numeric representation of the day of the week

		$descriptions = $mealDescriptions->eq($dayofweek)->filter('.day-alternative-wrapper .day-alternative span');

		$descriptions->each(function($description) use (&$output){
			$output['meals'][] = array(
				'description' => $description->text()
			);
		});
	}

	return $output;
}