<?php
function getSettings()
{
	static $settings;

	if (!$settings)
	{
		$settings_json = file_get_contents('settings.cfg');
		$settings = json_decode($settings_json);

		if (!$settings)
			throw new Exception('Config is invalid. It has to be in JSON format.');
	}

	return $settings;
}

function getSetting($setting = null)
{
	$settings = getSettings();

	if (isset($settings->$setting))
		return $settings->$setting;

	return null;
}