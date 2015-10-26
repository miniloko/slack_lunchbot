<?php
use Symfony\Component\DomCrawler\Crawler;

function sweden_skövde_orangeriet()
{
	// Restaurang Orangeriet
	// ---------------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://mattiasmat.se/');
	$mealDescriptions = $crawler->filter('#lunch-content-orangeriet h4');
	$output           = array();
	$weekdayMap       = array(
		0 => 'Måndag',
		1 => 'Tisdag',
		2 => 'Onsdag',
		3 => 'Torsdag',
		4 => 'Fredag',
	);
	$specialsMap = array(
		'Veckans'
	);

	if (count($mealDescriptions)) {
		$output['name'] = 'Orangeriet';
		$dayofweek = date('N'); // ISO-8601 numeric representation of the day of the week
		$descriptions = array();
		$mealDescriptions->each(function (Crawler $node, $i) use ($weekdayMap, $specialsMap, $dayofweek, &$descriptions) {
			$map = $specialsMap;

			if (!in_array($weekdayMap[$dayofweek - 1], $map)) {
				$map[] =  $weekdayMap[$dayofweek - 1];
			}

			foreach ($map as $value) {
				// Get current weekday lunch
				if (strpos($node->text(), $value) !== false) {
					if (in_array($value, $specialsMap)) {
						$description = $node->text() . ' ' .$node->nextAll()->first()->html();
					} else {
						$description = $node->nextAll()->first()->html();
					}

					$descriptions = array_merge($descriptions, explode('<br>', $description));
				}
			}

		});

		foreach ($descriptions as $description) {
			$output['meals'][] = array(
				'description' => $description
			);
		}
	}

	return $output;
}