<?php
define('DEBUG_LOCAL', false); // Outputs to error log instead of Slack if true
define('SLACK_ENDPOINT', ''); // Your endpoint url for Slack: https://hooks.slack.com/services/xxxxxx/xxxxxx/xxxxxxxxxx
define('SLACK_CHANNEL', '#food');
define('SLACK_BOT_NAME', 'Lunchbot');

define('FORMAT_LUNCH_BULLET', ':heavy_minus_sign:');
define('FORMAT_EATERS', ':information_desk_person:,:japanese_goblin:,:japanese_ogre:,:ok_woman:,:turtle:,:ox:,:octopus:,:tiger:,:frog:,:dancers:,:construction_worker:,:dromedary_camel:,:camel:');

define('RESTAURANTS', 'sweden/skövde/orangeriet,sweden/skövde/fortet,sweden/skövde/sand,sweden/skövde/glädje,sweden/skövde/alpina,sweden/skövde/vallegrillen');
