<?php
require_once 'vendor/autoload.php';

use Goutte\Client;

$client = new Client();

$data = array();

// 棋士ページをGET
$crawler = $client->request('GET', 'http://www.shogi.or.jp/player/pro/175.html');

// 名前を取得
$dom = $crawler->filter('div.nameArea');
$dom->each(function ($node) use (&$data) {
    $data['name'] = $node->filter('span')->eq(0)->text();
});

// 画像URLを取得
$dom = $crawler->filter('div.imgArea img');
$dom->each(function ($node) use (&$data) {
    $data['image'] = 'http://www.shogi.or.jp' . $node->attr('src');
});

// 基本情報を取得
$dom = $crawler->filter('div.uniqueLayoutElements03 table.tableElements02 tr');
$dom->each(function ($node) use (&$data) {
    $th = $node->filter('th')->text();
    $td = $node->filter('td')->text();
    if ($th == '棋士番号') {
        $data['no'] = $td;
    } else if ($th == '生年月日') { 
        $data['birthday'] = $td;
    } else if ($th == '出身地') { 
        $data['birthplace'] = $td;
    } else if ($th == '竜王戦') { 
        $data['ryuou'] = $td;
    } else if ($th == '順位戦') { 
        $data['junni'] = $td;
    }
});

echo json_encode($data);
