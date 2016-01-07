<?php
	#when do records search , comment bill, relase records
	require "MLPay.php";
	//const CH = 0;
	//const EN = 1;
	//global $LAN;//pred set $LAN
	//$LAN = CH;
	$data = array();
	$data["appid"] = "568a12086e912100017722e5";
	$data["token"] = "1eHwAapfyKgRufiBaoKNf63vyuCt_hHlkDdGncfGhBs";
	//bill
	$data["billNum"] = "112233";
	$data["channel"] = "ali_web";
	$data["totalFee"] = 1;
	$data["subject"] = "it will be ok!";
	$arrayName = array('a' => 1, 'b' => 2);
	$data["extras"] = $arrayName;
	$data["returnUrl"] = "http://www.qq.com";
	$data["billTimeout"] = 360;
	#$data["showUrl"] = "http://www.qq.com";
	#$data["qrPayMode"] = 0;
	$result = MLPayApi::bill($data);

	//records
	//$data["billNum"] = "112233";
	//$result = MLPayApi::record($data);
	$result->msg = getRespErr()[$result->code];
	print_r($result);

?>
