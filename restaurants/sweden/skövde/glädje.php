<?php

function sweden_skövde_glädje()
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

		$output['name'] = "Restaurang Glädje";

		foreach($mealTitles as $title) {
			$output['meals'][] = array(
				'description' => $title->textContent .', '.$mealDescriptions->eq($ix)->text()
			);

			$ix++;
		}
	}

	return $output;
}