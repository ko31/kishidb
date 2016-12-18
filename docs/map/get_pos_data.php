<?php
$key = 'AIzaSyAtslO_IFvo3f6CEZktmoEuBjhlC67FQ1g';

$data = file_get_contents("kishi_all.json");
$data = json_decode($data);

$maps = array();

foreach($data as $kishi) {
    $result = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($kishi->birthplace).'&key='.$key);
    $result = json_decode($result, true);
    if ($result['status'] != 'OK') {
        continue;
    }
    $maps[] = array(
        'no' => $kishi->no,
        'name' => $kishi->name,
        'birthplace' => $kishi->birthplace,
        'image' => $kishi->image,
        'lat' => $result['results'][0]['geometry']['location']['lat'],
        'lng' => $result['results'][0]['geometry']['location']['lng'],
        'sex' => $kishi->sex,
    );
    echo $kishi->no." ".$kishi->name."\n";
    usleep(500000);
}

$json = json_encode($maps);

file_put_contents('map.json', $json);
