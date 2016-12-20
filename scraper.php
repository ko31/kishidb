<?php
require_once 'vendor/autoload.php';

use Goutte\Client;

class Scraper {
    protected $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function getKishiLady($no, $format = 'array') {
        return $this->getKishi($no, $format, 2);
    }

    public function getKishi($no, $format = 'array', $sex = 1) {
        $data = array();

        // 棋士ページ
        if ($sex == 1) {
            $crawler = $this->client->request('GET', 'http://www.shogi.or.jp/player/pro/' . $no . '.html');
        // 女流棋士ページ
        } else {
            $crawler = $this->client->request('GET', 'http://www.shogi.or.jp/player/lady/' . $no . '.html');
        }

        // 名前を取得
        $dom = $crawler->filter('div.nameArea');
        $dom->each(function ($node) use (&$data) {
            $data['name'] = $node->filter('span')->eq(0)->text();
        });
        if (!$data) {
            // 将棋連盟サイトは404ページが無いので名前の取得で正常判断する
            return false;
        }

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
            } else if ($th == '師匠') { 
                $data['mentor'] = $td;
            } else if ($th == '竜王戦') { 
                $data['ryuou'] = $td;
            } else if ($th == '順位戦') { 
                $data['junni'] = $td;
            }
        });

        // 性別を取得
        // TODO：現時点では棋士 or 女流棋士で判断しているが女性棋士が誕生したらこれだとNG
        if ($sex == 1) {
            $data['sex'] = 'man';
        } else {
            $data['sex'] = 'woman';
        }

        if ($format == 'json') {
            return json_encode($data);
        } else {
            return $data;
        }
    }
}
