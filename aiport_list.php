<?php
/**
 * Returns JSON-encoded parsed list of airports from Wikipedia
 */
require_once __DIR__ . '/vendor/autoload.php';
$parser = new \WikipediaAirports\Parser();
echo json_encode($parser->wikiAPList());
