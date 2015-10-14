<?php

function sweden_skövde_coopforum()
{
	// Coop Forum
	// ---------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://gastrogate.com/restaurang/coopforum-skovde/page/3/');
	$mealDescriptions = $crawler->filter('.lunch_menu tr');
	$output           = array();

	if(count($mealDescriptions)) {
		$output['name'] = 'Coop Forum';
		$dayofweek = date('N') - 1; // ISO-8601 numeric representation of the day of the week

		$weeklyspecialindex = 5;
		$output['meals'] = $mealDescriptions
			->reduce(function ($node, $i) use ($dayofweek, $weeklyspecialindex) {
				return $i == $dayofweek * 3 + 1
					|| $i == $dayofweek * 3 + 2
					|| $i == $weeklyspecialindex * 3 + 1
					|| $i == $weeklyspecialindex * 3 + 2;
			})
			->filter('.td_title')
			->each(function ($node, $i) {
				return array('description' => $node->text());
			});
	}

	return $output;
}