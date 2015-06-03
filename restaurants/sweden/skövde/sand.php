<?php

function sweden_skövde_sand()
{
	$client     = getClient();
	$crawler    = $client->request('GET', 'http://sandmeza.se/lunchmeny/');
	$foodbox    = $crawler->filter('.menyblack .one-half')->last();
	$lunchDays  = $foodbox->filter('ul');
	$dayofweek  = date('N'); // ISO-8601 numeric representation of the day of the week

	if(count($lunchDays)) {
		$output['restaurant_name'] = "Restaurang Sand Meza";
		$lunchDay = $lunchDays->eq($dayofweek - 1)->children();

		foreach ($lunchDay as $meal) {
			$output['meals'][] = array(
				'description' => $meal->textContent
			);
		}
	}

	return $output;
}