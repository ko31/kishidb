<?php
$prefecture = array(
    array('北海道', 0),
    array('青森県', 0),
    array('岩手県', 0),
    array('宮城県', 0),
    array('秋田県', 0),
    array('山形県', 0),
    array('福島県', 0),
    array('茨城県', 0),
    array('栃木県', 0),
    array('群馬県', 0),
    array('埼玉県', 0),
    array('千葉県', 0),
    array('東京都', 0),
    array('神奈川県', 0),
    array('新潟県', 0),
    array('富山県', 0),
    array('石川県', 0),
    array('福井県', 0),
    array('山梨県', 0),
    array('長野県', 0),
    array('岐阜県', 0),
    array('静岡県', 0),
    array('愛知県', 0),
    array('三重県', 0),
    array('滋賀県', 0),
    array('京都府', 0),
    array('大阪府', 0),
    array('兵庫県', 0),
    array('奈良県', 0),
    array('和歌山県', 0),
    array('鳥取県', 0),
    array('島根県', 0),
    array('岡山県', 0),
    array('広島県', 0),
    array('山口県', 0),
    array('徳島県', 0),
    array('香川県', 0),
    array('愛媛県', 0),
    array('高知県', 0),
    array('福岡県', 0),
    array('佐賀県', 0),
    array('長崎県', 0),
    array('熊本県', 0),
    array('大分県', 0),
    array('宮崎県', 0),
    array('鹿児島県', 0),
    array('沖縄県', 0),
);

$data = file_get_contents("kishi_all.json");
$data = print_r(json_decode($data), true);

$json = array();
$json[] = array('都道府県', '棋士数');

foreach($prefecture as $key => $val) {
    $count = mb_substr_count($data, $val[0]);
    echo $val[0] ." = ".$count."\n";
    $json[] = array($val[0], $count);
}

file_put_contents("data.json", json_encode($json));
