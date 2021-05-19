<?php

//Getting sealevel observations for Rørvik (Hytta) from Kartverket API

class SeaLevel {

	private $lat;
	private $lon;
	private $from_time;
	private $to_time;
	private $diveBoardHight;
	private $waterLevelOffset;
	private $waterLevelOffsetBardoy;
	private static $isDaylightSaving;

	public function __construct($lat = "64.613032",$lon = "11.222156") { //Default Tømmervika/Rørvik
		$this->lat = $lat;
		$this->lon = $lon;
		$this->from_time = date('Y-m-d');
		$this->to_time = date('Y-m-d',strtotime("+ 1 day"));
		$this->diveBoardHight = 803; //The hight from the diving board down to the sea bottom
		$this->waterLevelOffset = 320; //How many cm to add to the curretn water level to get the depth from water surface to the bottom
		$this->waterLevelOffsetBardoy = 50;
		self::$isDaylightSaving = date('I');
	}

	private function currentTime() {
		if($this->isDaylightSaving) {
			return date('Y-m-d\TH:i:s',strtotime('+1 hours'));
		} else {
			return date('Y-m-d\TH:i:s',strtotime('+2 hours'));
		}
	}


	public function getTides() {
		$datatype = "tab";
		$url = "http://api.sehavniva.no/tideapi.php?lat={$this->lat}&lon={$this->lon}&fromtime={$this->from_time}&totime={$this->to_time}&datatype=$datatype&refcode=cd&lang=nb&interval=10&dst=1&tide_request=locationdata";
		
		$xml = file_get_contents($url);

		$data = simplexml_load_string($xml);
		$json = json_encode($data);
		$array = json_decode($json,true);

		$tides = $array['locationdata']['data'];
		$tidesArray = [];
		foreach($tides['waterlevel'] as $key => $value) {
			$tidesArray[] = $value['@attributes'];
		}

		return $tidesArray;
	}

	public function getCurrentSealevel() {
		$datatype = "all";

		$url = "http://api.sehavniva.no/tideapi.php?lat={$this->lat}&lon={$this->lon}&fromtime={$this->from_time}&totime={$this->to_time}&datatype=$datatype&refcode=cd&lang=nb&interval=10&dst=1&tide_request=locationdata";
		$xml = file_get_contents($url);

		$data = simplexml_load_string($xml);
		$json = json_encode($data);
		$array = json_decode($json,true);

		$forecasts = $array['locationdata']['data'][3]['waterlevel'];

		$currentForcast = null;

		foreach($forecasts as $forecast) {
			$time = $forecast['@attributes']['time'];
			if($time > date('Y-m-d\TH:i:s',strtotime('+2 hours'))) {
				$currentForcast['time'] = $lastForecast['@attributes']['time'];
				$currentForcast['value'] = round($lastForecast['@attributes']['value']);
				$currentForcast['direction'] = $lastForecast['@attributes']['value'] > $forecast['@attributes']['value'] ? "decreasing" : "increasing";
				break;
			}
			$lastForecast = $forecast;
		}

		return $currentForcast;
	}

	public function getBardoyDepth() {
		$level = $this->getCurrentSealevel();
		$depth = $level['value'] + $this->waterLevelOffsetBardoy;

		return [
			'depth' => $depth,
			'time' => $level['time']
		];
	}

	public function getDiveBoardNumbers() {
		$level = $this->getCurrentSealevel();
		$depth = $level['value'] + $this->waterLevelOffset;
		$hight = $this->diveBoardHight - $depth;
		
		return [
			'depth' => $depth,
			'hight' => $hight,
			'time' => $level['time']
		];
	}

	public static function formatTime($t) {
		if(self::$isDaylightSaving ) { //Check if daylight saving is in affect and add hours accordingly
			$time = strtotime($t . " +1 hours");
		} else {
			$time = strtotime($t . " +2 hours");
		}
		
		return date('H:i',$time);
	}

}

$sl = new SeaLevel();
$currentSeaLevel = $sl->getCurrentSealevel();

$pages = ['home','stupebrett','bardoysundet'];
if(isset($_GET['p']) && in_array($_GET['p'], $pages)) {
	$page = $_GET['p'];
} else {
	$page = "home";
}

if($page == "home") {
	$tides = $sl->getTides();
} else if($page == "stupebrett") {
	$diveBoard = $sl->getDiveBoardNumbers();
} else if($page == "bardoysundet") {
	$bardoy = $sl->getBardoyDepth();
}




include("pages/main.php");


?>