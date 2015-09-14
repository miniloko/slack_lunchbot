# Installation

1. Install the [Composer](https://getcomposer.org/) dependencies
2. Copy `settings.sample.php` to `settings.php`
3. Set up an [Incoming Webhooks integration](https://api.slack.com/incoming-webhooks) and add the URL to your `slack_endpoint` in `settings.cfg` and set the appropriate `slack_channel` to post in.

# Setting up your own restaurant

1. Create a new file in `restaurants/$COUNTRY/$CITY/` (create the folder if nessesary) (e.g. `restaurants/France/Paris/`)
   1. Name this file after the restaurant (e.g. `macéo.php`)
2. Create a function in the file named after its path (e.g. `restaurants/France/Paris/macéo.php` should have a function called `france_Paris_macéo`)
3. Make sure this function returns an array with the following structure:

```php
   array(
      'name' => $name,
      'meals' => array(
         array(
            'description' => $meal_description_1
         ),
         array(
            'description' => $meal_description_2
         )...
      )
   )
```

:grey_exclamation: *Tips:* You can use `getClient()` to get an instance of [Goutte](https://github.com/FriendsOfPHP/Goutte).
