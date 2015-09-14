<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__.DS);

function lunchbot_include($file_path)
{
	if (file_exists($file_path))
	{
		require_once ROOT.$file_path;
		return true;
	}

	return false;
}

function lunchbot_init()
{
	lunchbot_include('crawl.php');
	lunchbot_include('restaurant.php');
	lunchbot_include('settings.php');
	lunchbot_include('slack.php');

	try
	{
		$restaurants = restaurant_get();

		if (empty($restaurants))
			throw new Exception('No restaurants found.');

		$messages = [];

		if ($greeting = getSetting('greeting'))
		{
			if (is_array($greeting))
				$greeting = $greeting[array_rand($greeting)];

			$messages[] = sprintf('```%s```', trim($greeting));
			$messages[] = "Here's what's available today:";
		}

		foreach ($restaurants as $restaurant)
		{
			$messages[] = restaurant_format($restaurant);
		}

		$message = implode("\n", $messages);

		lunchbot_post($message);
	}
	catch (Exception $e)
	{
		echo sprintf('Bot response: %s', $e->getMessage())."\n";
		exit;
	}
}

function lunchbot_post($message)
{
	if (!getSetting('debug'))
	{
		$response = slack_post($message);

		if (!$response)
			throw new Exception('No response from Slack.');
	}
	else
	{
		echo sprintf("BOT: \n%s\n", $message);
		return true;
	}
}

lunchbot_init();
