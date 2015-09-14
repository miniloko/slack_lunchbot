<?php

function slack_post($message, $channel = null)
{
	$slack_endpoint = getSetting('slack_endpoint');
	$slack_channel = ($channel ? $channel : getSetting('slack_channel'));
	$slack_bot_name = getSetting('slack_bot_name');
	$slack_icon = getSetting('slack_icon');

	if (empty($slack_endpoint))
		throw new Exception('No endpoint set.');

	if (empty($slack_channel))
		throw new Exception('No channel set.');

	if (empty($slack_bot_name))
		throw new Exception('No bot name set.');

	if (empty($slack_icon))
		throw new Exception('No bot icon set.');

	// Add hashtag if none set
	if (strpos($slack_channel, '#') !== 0) $slack_channel = '#'.$slack_channel;

	// If Slack icon is array, choose a random one
	if (is_array($slack_icon))
		$slack_icon = $slack_icon[array_rand($slack_icon)];

	$data = json_encode(array(
			"channel"       =>  $slack_channel,
			"username"      =>  $slack_bot_name,
			"text"          =>  $message,
			"icon_emoji"    =>  $slack_icon
	), JSON_UNESCAPED_UNICODE);

	$ch = curl_init($slack_endpoint);

	curl_setopt_array($ch, array(
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_RETURNTRANSFER => true
	));

	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}