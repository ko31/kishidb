<?php
$data = file_get_contents("kishi_all.json");
$data = json_decode($data);

$node_names = array();
$node_links = array();
$node_no = array();
$node_count = array();

$search = array(
    " ",
    "　",
    "四段",
    "五段",
    "六段", 
    "七段", 
    "八段", 
    "九段", 
    "(故)", 
    "(故）", 
    "（故)", 
    "（故）", 
    "名誉", 
    "十三世名人", 
    "十四世名人", 
    "十五世名人", 
    "十六世名人", 
    "実力制第四代名人", 
    "名人", 
    "・王将",
    "十段",
    "永世棋聖"
);

//棋士名取得
foreach ($data as $kishi) {
    $name = $kishi->name;
    $node_names[] = array("name" => $name);
    $node_no[$name] = count($node_no);
}

//師匠名取得
foreach ($data as $kishi) {
    $mentor = str_replace($search, "", $kishi->mentor);
    if (!$mentor) {
        continue;
    }
    if (!isset($node_count[$mentor])) {
        $node_count[$mentor] = 1;
    } else {
        $node_count[$mentor]++; 
    }
    if (isset($node_no[$mentor])) {
        continue;
    }
    $node_names[] = array("name" => $mentor);
    $node_no[$mentor] = count($node_no);
}

//棋士と師匠の関連
foreach ($data as $kishi) {
    $name = $kishi->name;
    $mentor = str_replace($search, "", $kishi->mentor);
    if (!$mentor) {
        continue;
    }
    $node_links[] = array(
        "source" => $node_no[$name],
        "target" => $node_no[$mentor],
        "value" => $node_count[$mentor],
    );
}

$json = array(
    "nodes" => $node_names,
    "links" => $node_links,
);

$json = json_encode($json);

file_put_contents("sankey.json", $json);
