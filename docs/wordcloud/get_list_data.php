<?php
$data = file_get_contents("kishi_all.json");
$data = json_decode($data);

$list = array();
foreach ($data as $kishi) {
    $length = mb_strlen($kishi->name, 'UTF-8');
    for($i=0; $i<$length; $i++) {
        $char = mb_substr($kishi->name, $i, 1, 'UTF-8');
        if (isset($list[$char])) {
            $list[$char]++;
        } else {
            $list[$char] = 1;
        }
    }
}

$tmp = array();
foreach ($list as $k => $v) {
    $tmp[] = "['".$k."', ".($v*5)."]";
}

$src = 'var data = [';
$src .= implode(',', $tmp);
$src .= '];';

file_put_contents('data.js', $src);
