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
	$data["appid"] = "568a12086e912100017722e5";
        $data["token"] = "1eHwAapfyKgRufiBaoKNf63vyuCt_hHlkDdGncfGhBs";
	if (isset($argv[1]) && $argv[1] == "bill"){
		$data["channel"] = $argv[2]?$argv[2]:null;
		$data["totalFee"] = 1;
		$data["subject"] = "it will be ok!";
		$arrayName = array('a' => 1, 'b' => 2);
		$data["extras"] = $arrayName;
		$data["returnUrl"] = "http://www.qq.com";
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
