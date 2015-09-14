<?php

function sweden_skövde_alpina()
{
	// Restaurang Alpina
	// -----------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://www.restaurangalpina.se/');
	$foodbox          = $crawler->filter('.sidebar .view-lunch')->first();
	$mealDescriptions = $foodbox->filter('.vh-vad, .field-item');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['name'] = 'Alpina';

		foreach ($mealDescriptions as $mealDescription) {
			$trimmedFood = trim($mealDescription->textContent);
			if (!empty($trimmedFood))
			$output['meals'][] = array(
				'description' => $trimmedFood
			);
		}
	}

	return $output;
}