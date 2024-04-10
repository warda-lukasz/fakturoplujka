<?php

require('vendor/autoload.php');

use Enums\Color;
use Enums\Format;
use Utils\PromptHelper;
use Utils\TexManager;

$prompt = new PromptHelper();
$prompt->startClock();

$prompt->printl('Working... 🧐', Color::LightCyan, Format::Bold, Format::Underline);
$prompt->printEmptyLine(1);

$texManager = new TexManager();
$texManager->parseTemplate();

$prompt->printDivider('-', amount: 45, color: Color::Green, format: Format::Bold);
$prompt->printl('Done... 😎  Check your output folder 👌');
$prompt->printl("Total execution time: " . $prompt->stopClockString());
$prompt->printDivider('-', amount: 45, color: Color::Green, format: Format::Bold);
$prompt->printEmptyLine(1);
