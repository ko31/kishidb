<?php
class YahooGeocoder {
    protected $yahoo_app_id;

    public function __construct() {
        $this->setAppId();
    }

    public function setAppId() {
        $this->yahoo_app_id = $_ENV['YAHOO_APP_ID'];
    }

    public function isEnable() {
        return (bool)$this->yahoo_app_id;
    }

    public function geocoding($address) {
        $locations = $this->coverup($address);
        if ($locations) {
            return $locations;
        }

		// Request API
		$url = sprintf( 'https://map.yahooapis.jp/geocode/V1/geoCoder?appid=%s&output=json&query=%s', $this->yahoo_app_id, urlencode( $address ) );
		$ch  = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$results = curl_exec($ch);
		curl_close($ch);

		$results = json_decode($results);

		// Check response
		if (empty($results) || !is_object($results)) {
			return null;
		}
		if (strcmp($results->ResultInfo->Status, '200') !== 0) {
			return null;
		}
        if (!property_exists($results, 'Feature')) {
            return null;
        }
		if (!$locations = explode(',', $results->Feature[0]->Geometry->Coordinates)) {
			return null;
		}

		return $locations;
    }

    // Covering addresses that the Yahoo API can't get.
    public function coverup($address) {
        $locations = [
            '鳥取県岸本町'              => [133.4126, 35.38477],
            '広島県三良坂町'            => [132.9684, 34.76253],
            '新潟県分水町'              => [138.8822, 37.67309],
            '兵庫県三原郡'              => [134.5453, 34.85795],
            '鳥取県赤碕町'              => [133.6399, 35.51341],
            '山梨県増穂町'              => [138.4657, 35.56905],
            '宮城県塩釜市'              => [141.0220, 38.31437],
            '茨城県潮来町'              => [140.5554, 35.94712],
            '高知県中村市'              => [132.9439, 32.98457],
            '秋田県大曲市'              => [140.4955, 39.43526],
            '秋田県大曲市(現：大仙市)'  => [140.4955, 39.43526],
            '愛媛県伊予三島市'          => [132.6981, 33.74883],
            '千葉県八日市場市'          => [140.5614, 35.70438],
            '鹿児島県霧島町'            => [130.7633, 31.74073],
            '秋田県大仙市'              => [140.4754, 39.45327],
            'ワルシャワ（ポーランド）'  => [21.0122, 52.22967],
        ];

        if (isset($locations[$address])) {
            return $locations[$address];
        }

		return null;
    }
}
