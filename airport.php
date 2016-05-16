#!/usr/bin/php
<?php
/**
 * Returns JSON-encoded airport info from Wikipedia
 */
require_once __DIR__ . '/vendor/autoload.php';
$parser = new \WikipediaAirports\Parser();
echo json_encode($parser->parseAirport($argv[1]));
