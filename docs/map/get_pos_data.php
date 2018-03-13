<?php
$key = 'AIzaSyAtslO_IFvo3f6CEZktmoEuBjhlC67FQ1g';

$data = file_get_contents("kishi_all.json");
$data = json_decode($data);

$maps = array();
$positions = array();

foreach($data as $kishi) {
    $result = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($kishi->birthplace).'&key='.$key);
    $result = json_decode($result, true);
    if ($result['status'] != 'OK') {
        continue;
    }
    $lat = $result['results'][0]['geometry']['location']['lat'];
    $lng = $result['results'][0]['geometry']['location']['lng'];
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
    echo $kishi->no." ".$kishi->name."\n";
//    usleep(500000);
    usleep(250000);
}

$json = json_encode($maps);

file_put_contents('map.json', $json);
