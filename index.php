#!/usr/bin/php
<?php
require 'config.php';
require 'fugly-restaurant-crawlr.php';

if(!defined('SLACK_ENDPOINT')) return;

echo postMessage() ."\n";

// CODE! :D

function postMessage()
{
	$slackEndpoint = SLACK_ENDPOINT;

	$icons = array(
		':hamburger:',
		':pizza:',
		':curry:',
		':spaghetti:',
		':meat_on_bone:',
		':sushi:',
		':poultry_leg:',
		':ramen:',
		':fries:',
		':bread:'
	);
	$restaurants = crawlAll();
	$message = buildMessageText($restaurants);

	$data = json_encode(array(
		"channel"       =>  SLACK_CHANNEL,
		"username"      =>  SLACK_BOT_NAME,
		"text"          =>  $message,
		"icon_emoji"    =>  $icons[array_rand($icons)]
	), JSON_UNESCAPED_UNICODE);

	$ch = curl_init($slackEndpoint);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function buildMessageText($restaurants, $showWelcome = true)
{
	$output = '';
	$eaters = explode(FORMAT_EATERS, ',');

	$welcomeTexts = array(
		'Are you feeling Hungary?',
		'お腹hあすきましたか？',
		'I wonder what ' . $eaters[array_rand($eaters)] . ' would eat... Anyway,',
		'LUNCHTIME!',
		'Time for some face-stuffing!',
	);

	if($showWelcome) {
		$output = "*" . $welcomeTexts[array_rand($welcomeTexts)] . "*\n\n";
		$output .= "_Here's what's available today:_\n\n";
	}

	foreach ($restaurants as $restaurant) {
		$output .= formatRestaurant($restaurant);
		$output .= "\n";
	}

	return $output;
}

function formatRestaurant($restaurant = array())
{
	$output = "~— *{$restaurant['restaurant_name']}* —~\n";

	foreach ($restaurant['meals'] as $meal) {

		if(isset($meal['description']))
		{
			$output .= FORMAT_LUNCH_BULLET .' '. trim(strip_tags($meal['description']));
		}

		$output .= "\n";
	}

	return $output;
}