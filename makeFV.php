<?php

require('vendor/autoload.php');

use Utils\TexManager;

$time_start = microtime(true);
echo "Working... 🧐".PHP_EOL;

$texManager = new TexManager();
$texManager->parseTemplate();

echo '--------------------------------------'.PHP_EOL;
echo 'Done... 😎  Check your output folder 👌'.PHP_EOL;
echo 'Total execution time: ' . round(((microtime(true) - $time_start)*1000),2).'ms'.PHP_EOL;
