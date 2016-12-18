<?php
require_once 'scraper.php';

$scraper = new Scraper();

$data = array();

for ($i=1; $i<=308; $i++) {
    $_kishi = $scraper->getKishi($i);
    if ($_kishi) {
        $data[] = $_kishi;
echo $_kishi['no'].$_kishi['name']."\n";
    }
    usleep(500000);
}

for ($i=1; $i<=57; $i++) {
    $_kishi = $scraper->getKishiLady($i);
    if ($_kishi) {
        $data[] = $_kishi;
echo $_kishi['no'].$_kishi['name']."\n";
    }
    usleep(500000);
}

file_put_contents("kishi_all.json", json_encode($data));
