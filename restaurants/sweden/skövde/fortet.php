<?php

function sweden_skövde_fortet()
{
	$output     = array(
		'name' => 'Fortet',
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

	return $output;
}