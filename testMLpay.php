<?php
	#when do records search , comment bill, relase records
	require "MLPay.php";
	//const CH = 0;
	//const EN = 1;
	//global $LAN;//pred set $LAN
	//$LAN = CH;
	$data = array();
	//bill
	$data["billNum"] = "112233";
	$data["appid"] = "56936325a5ff7f0001a27b89";
    $data["token"] = "CchNJ1A-yo9X6nY2HNaCU3yJ3qCtTxHljTFWhHr-l5k";
	if (isset($argv[1]) && $argv[1] == "bill"){
		$data["channel"] = $argv[2]?$argv[2]:null;
		$data["totalFee"] = 1;
		$data["subject"] = "it will be ok!";
		$arrayName = array('a' => 1, 'b' => 2);
		$data["extras"] = $arrayName;
		$data["returnUrl"] = "http://101.231.204.84:11006/ACPTest/FrontRcvResponse.do";
		$data["billTimeout"] = 360;
		//$data["showUrl"] = "http://www.qq.com";
		//$data["qrPayMode"] = 0;
		$result = MLPayApi::bill($data);
	}elseif(isset($argv[1]) && $argv[1] == "record"){
		//records
		$result = MLPayApi::record($data);
		$result->msg = getRespErr()[$result->code];
	}else{
		die("no way celect");
	}
	print_r($result);

?>
