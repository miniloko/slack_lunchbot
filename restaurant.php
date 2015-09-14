<?php
function restaurant_get()
{
	$restaurants_settings = getSetting('restaurants');
	$restaurants_settings = array_unique($restaurants_settings);

	static $restaurants = array();

	if (!empty($restaurants)) return $restaurants;

	foreach ($restaurants_settings as $restaurants_setting)
	{
		$restaurant = restaurant_load($restaurants_setting);

		if ($restaurant)
			$restaurants[] = $restaurant;
	}

	return $restaurants;
}

function restaurant_load($restaurant)
{
	$pieces = mb_split('/', $restaurant);
	$file_path = 'restaurants'.DS. mb_convert_case(implode(DS, $pieces), MB_CASE_LOWER, 'UTF-8').'.php';
	$function = mb_convert_case(implode('_', $pieces), MB_CASE_LOWER, 'UTF-8');

	if (!lunchbot_include($file_path))
		throw new Exception(sprintf('Error loading file "%s".', $file_path));

	if (function_exists($function))
		return call_user_func($function);
	else
		throw new Exception(sprintf('Could not find function "%s" in file "%s".', $function, $file));
}


function restaurant_format($restaurant = array())
{
	static $template;

	if (!$template)
		$template = trim(file_get_contents('restaurant.tpl.php'));

	$output = str_replace('{name}', $restaurant['name'], $template);

	$regex = '/\{meals\}([\s\S]*)\{\/meals\}/';

	preg_match($regex, $template, $template_meal);
	$template_meal = (isset($template_meal[1]) ? trim($template_meal[1]) : '');

	$meals = [];

	foreach ($restaurant['meals'] as $meal)
	{
		if (isset($meal['description']))
			$meals[] = str_replace('{meal}', str_replace(array("\r", "\n"), '', trim(strip_tags($meal['description']))), $template_meal);
	}

	$output = preg_replace($regex, implode("\n", $meals), $output);

	return $output;
}