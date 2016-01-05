<?php
	require "MLPay.php";
	const CH = 0;
	const EN = 1;
	global $LAN;//pred set $LAN
	$LAN = EN;
	$data = array();
	$data["appid"] = "557e5129c10eeabb4878bb38";
	$data["token"] = "Lm1bckeriwjPy5ECVglXKPdbH7CtPhHljhpWhHr-l5k";
	//bill
	$data["billNum"] = "112233";
	$data["channel"] = "ali_web";
	$data["totalFee"] = 1;
	$data["subject"] = "it will be ok!";
	$extra = ["a"=>1,"b"=>2];
	$jextra = json_encode($exrta);
	$data["extras"] = $jextra;
	$data["returnUrl"] = "http://www.qq.com";
	$data["billTimeout"] = 360;
	$data["showUrl"] = "http://www.qq.com";
	$data["qrPayMode"] = 0;
	$result = MLPayApi::bill($data);

	//records
	//$data["billNum"] = "112233";
	//$result = MLPayApi::record($data);
	$result->msg = getRespErr()[$result->code];
	print_r($result);

?>
