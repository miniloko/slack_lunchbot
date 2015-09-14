<?php
use Symfony\Component\DomCrawler\Crawler; // Prevents an error in ->reduce()

function sweden_skövde_wallermans()
{
	// Restaurang Wallermans
	// -----------------

	$client           = getClient();
	$crawler          = $client->request('GET', 'http://www.wallermans.se/Dagens-lunch.html');
	$foodbox          = $crawler->filter('[id^=swc_MenuHorizontalText] + table td')->last();
	$mealDays         = $foodbox->filter('h3');
	$output           = array();

	// This restaurant outputs some form of whitespace not catched by the native trim() - this should cover most cases.
	// TODO: Make accessible from other restaurants.
	$whitespace = array(
		"SPACE" => "\x20",
		"TAB" => "\x09",
		"NEWLINE" => "\x0A",
		"CARRIAGE_RETURN" => "\x0D",
		"NULL_BYTE" => "\x00",
		"VERTICAL_TAB" => "\x0B",
		"NO-BREAK SPACE" => "\xc2\xa0",
		"OGHAM SPACE MARK" => "\xe1\x9a\x80",
		"EN QUAD" => "\xe2\x80\x80",
		"EM QUAD" => "\xe2\x80\x81",
		"EN SPACE" => "\xe2\x80\x82",
		"EM SPACE" => "\xe2\x80\x83",
		"THREE-PER-EM SPACE" => "\xe2\x80\x84",
		"FOUR-PER-EM SPACE" => "\xe2\x80\x85",
		"SIX-PER-EM SPACE" => "\xe2\x80\x86",
		"FIGURE SPACE" => "\xe2\x80\x87",
		"PUNCTUATION SPACE" => "\xe2\x80\x88",
		"THIN SPACE" => "\xe2\x80\x89",
		"HAIR SPACE" => "\xe2\x80\x8a",
		"ZERO WIDTH SPACE" => "\xe2\x80\x8b",
		"NARROW NO-BREAK SPACE" => "\xe2\x80\xaf",
		"MEDIUM MATHEMATICAL SPACE" => "\xe2\x81\x9f",
		"IDEOGRAPHIC SPACE" => "\xe3\x80\x80",
	);

	if(count($mealDays)) {
		$output['name'] = 'Restaurang Wallermans';
		$dayofweek = date('N'); // ISO-8601 numeric representation of the day of the week
		$mealsToday = $mealDays->eq($dayofweek - 1);
		$continueReduce = true; // Once this is flipped, the reduce function always returns false

		// The days starts with h3's, followed by x number of p's.
		$paragraphsUntilH3 = $mealsToday->nextAll()->reduce(function (Crawler $node, $i) use (&$continueReduce) {
			// If we encounter an h3, we should remove it and everything following.
			if ($node->nodeName() === 'h3') {
				$continueReduce = false;
			}
			// .newsheading
			if ($node->attr('class') === 'newsheading' || $node->nodeName() === 'em') {
				return false;
			}

			return $continueReduce;
		});

		foreach ($paragraphsUntilH3 as $mealDescription) {
			$trimmedFood = trim($mealDescription->textContent, implode(array_values($whitespace), ''));

			if (!empty($trimmedFood)) {
				$output['meals'][] = array(
					'description' => $trimmedFood
				);
			}
		}
	}

	return $output;
}