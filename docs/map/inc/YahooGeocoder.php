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
}
