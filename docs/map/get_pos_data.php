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
    // Yahoo API は 137.21368220,36.69601510 のように細かい単位で取得されるので、
    // 少し上位の桁にまるめておく。
    $lng = round($locations[0], 4);
    $lat = round($locations[1], 5);

    // 同じ座標が存在する場合、重ならないよう少しずつ位置をずらす。
    $_position = $lat . "," . $lng;
    if (isset($positions[$_position])) {
        $lat += $positions[$_position] * 0.0001;
        $lng += $positions[$_position] * 0.001;
        $positions[$_position]++;
    } else {
        $positions[$_position] = 1;
    }

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
    usleep(300000);
}

$json = json_encode($maps);

file_put_contents('map.json', $json);

echo "done\n";
