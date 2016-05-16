#!/usr/bin/php
<?php
/**
 * Gets data from airport_list.php to STDIN
 * Returns expanded data
 */

$json = '';
while ($s = fgets(STDIN)) {
    $json .= $s;
}
$list = json_decode($json, true);

foreach ($list as &$ap) {
    if ($ap['name_links']) {
        $path = $ap['name_links'][0];
        if (substr($path, 0, 5) !== '/wiki') {
            continue;
        }
        $url = "https://en.wikipedia.org$path";
        $ext = json_decode(`php airport.php "$url"`, true);
        $ap = array_merge($ap, $ext);
    }
}
echo json_encode($list);
