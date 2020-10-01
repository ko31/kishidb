<?php
require_once 'scraper.php';

$scraper = new Scraper();

$data = array();

for ($i=1; $i<=326; $i++) {
echo "$i\n";
    $_kishi = $scraper->getKishi($i);
    if ($_kishi) {
        $data[] = $_kishi;
    }
}

for ($i=1; $i<=70; $i++) {
echo "$i\n";
    $_kishi = $scraper->getKishiLady($i);
    if ($_kishi) {
        $data[] = $_kishi;
    }
}

//for ($i=6002; $i<=6007; $i++) {
//echo "$i\n";
//    $_kishi = $scraper->getKishiLady($i);
//    if ($_kishi) {
//        $data[] = $_kishi;
//    }
//}

$output = '';
foreach ($data as $row) {
    $no = (($row['sex'] == 'woman') ? 'l-' : '') . $row['no'];
    $output .= $row['name'].'$'.$no;
    $output .= "\n";
}
file_put_contents("output.txt", $output);
file_put_contents("kishi_all.json", json_encode($data));
echo "done\n";
