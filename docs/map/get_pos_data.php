<?php
require './vendor/autoload.php';

require_once 'inc/YahooGeocoder.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$YahooGeocoder = new YahooGeocoder();
if (!$YahooGeocoder->isEnable()) {
    exit('Cannot geocoding.');
}

$data = file_get_contents("kishi_all.json");
$data = json_decode($data);

$maps = array();
$positions = array();

foreach($data as $kishi) {
    $locations = $YahooGeocoder->geocoding($kishi->birthplace);
    if (!$locations) {
        echo "{$kishi->no} {$kishi->name} Geocoding failed.\n";
        continue;
    }
    $lng = $locations[0];
    $lat = $locations[1];

    if (in_array($lat . "," . $lng, $positions)) {
        $lat -= 0.00005;
        $lng += 0.0005;
    }
    $positions[] = $lat . "," . $lng;
    $maps[] = array(
        'no' => $kishi->no,
        'name' => $kishi->name,
        'birthplace' => $kishi->birthplace,
        'image' => $kishi->image,
        'lat' => $lat,
        'lng' => $lng,
        'sex' => $kishi->sex,
    );
    echo "{$kishi->no} {$kishi->name} {$kishi->birthplace} $lng,$lat\n";
    usleep(250000);
}

$json = json_encode($maps);

file_put_contents('map.json', $json);

echo "done\n";
