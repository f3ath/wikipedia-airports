<?php
namespace WikipediaAirports;

use DOMDocument;
use DOMXPath;
use DOMNode;
use PHPCurl\CurlHttp\HttpClient;

class Parser
{
    /**
     * @var DOMXPath
     */
    private $xpath;

    public function wikiAPList()
    {
        $data = [];
        for ($letter = 'A'; $letter <= 'Z'; $letter = chr(ord($letter) + 1)) {
            $this->log("Parsing $letter");
            $this->open("https://en.wikipedia.org/wiki/List_of_airports_by_IATA_code:_$letter");
            $rows = $this->xpath->query('//tr[not(@class)]');
            for ($i = 0; $i < $rows->length; $i++) {
                $row = $rows->item($i);
                $cells = $this->xpath->query('td', $row);
                if ($cells->length < 3) {
                    continue;
                }
                $data[] = [
                    'iata'           => substr(trim($cells->item(0)->nodeValue), 0, 3),
                    'icao'           => substr(trim($cells->item(1)->nodeValue), 0, 4),
                    'name'           => trim($cells->item(2)->nodeValue),
                    'name_links'     => $this->getLinks($cells->item(2)),
                    'location'       => trim($cells->item(3)->nodeValue),
                    'location_links' => $this->getLinks($cells->item(3)),
                ];

            }
        }
        return $data;
    }

    /**
     * Open URL
     * @param string $url
     */
    private function open(string $url)
    {
        $http = new HttpClient();
        $http->setOptions([
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $html = $http->get($url)->getBody();
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $this->xpath = new DOMXPath($dom);
    }

    /**
     * get array of all a-href links
     * @param DOMNode  $node
     * @return array
     */
    private function getLinks(DOMNode $node): array
    {
        $links = [];
        $anchors = $this->xpath->query('a', $node);
        for ($i = 0; $i < $anchors->length; $i++) {
            $links[] = $anchors->item($i)->attributes->getNamedItem('href')->nodeValue;
        }
        return $links;
    }

    /**
     * @param string $msg
     */
    private function log(string $msg)
    {
        error_log($msg);
    }
}
