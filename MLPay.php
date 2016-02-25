<?php
/*
##### _Author: Kevin
##### _Github: https://github.com/lalamini
*/

global $LAN;
function getErrMsg(){
global $LAN;
if (!isset($LAN)){
	$LAN = 1;
}
return [
	"unexpected_result" => $LAN ? "UNEXPECTED_RESULT:":"非预期的返回结果:",
	"need_param" => $LAN ? "NEED_PARAM:":"需要必填字段:",
	"need_valid_param" => $LAN ? "NEED_VALID_PARAM:":"字段值不合法:",
	"need_resturn_url" => $LAN ? "NEED_RETURN_URL:":"当channel参数为 ALI_WEB 或 ALI_QRCODE 或 UN_WEB时 return_url为必填",
	"bill_timeout_error" => $LAN ? "BILL_TIMEOUT_ERROR":"当channel参数为 JD* 或 KUAIQIAN* 不支持bill_timeout",
	"not_support_way" => $LAN ? "NOT_SUPPORT_WAY":"不支持的HTTP方式",
];
}

function getRespErr(){
	global $LAN;
	if (!isset($LAN)){
		$LAN = 1;
	}
	return [
		0 => $LAN ? 'OK':"成功",
		1 => $LAN ? 'APP_INVALID':'根据app_id找不到对应的APP或者app_sign不正确',
		2 => $LAN ? 'PAY_FACTOR_NOT_SET':'支付要素在后台没有设置',
	    3 => $LAN ? 'CHANNEL_INVALID':'channel参数不合法',
	    4 => $LAN ? 'MISS_PARAM':'缺少必填参数',
		5 => $LAN ? 'PARAM_INVALID':'参数不合法',
		6 => $LAN ? 'CERT_FILE_ERROR':'证书错误',
		7 => $LAN ? 'CHANNEL_ERROR':'渠道内部错误',
		14 => $LAN ? 'RUN_TIME_ERROR':'实时未知错误，请与技术联系帮助查看',
	];
}

class MLRESTErrMsg {
	const UNEXPECTED_RESULT = "非预期的返回结果:";
	const NEED_PARAM = "需要必填字段:";
	const NEED_VALID_PARAM = "字段值不合法:";
	const NEED_WX_JSAPI_OPENID = "微信公众号支付(WX_JSAPI) 需要openid字段";
	const NEED_RETURN_URL = "当channel参数为 ALI_WEB 或 ALI_QRCODE 或 UN_WEB时 return_url为必填";
	const BILL_TIMEOUT_ERROR = "当channel参数为 JD* 或 KUAIQIAN* 不支持bill_timeout";
}
class MLRESTUtil {
	static final public function getApiUrl() {
		$domain = "apiuat.maxleap.com";// for uat test
		//$domain = "apidev.maxleap.com";//for dev test
		
		//$domain = "https://webuat.maxleap.cn/maxpay"; //for prod 
		return "http://" . $domain;
		//return $domain;
	}
	static final public function request($url, $method, array $data, $timeout) {
		try {
			$timeout = (isset($timeout) && is_int($timeout)) ? $timeout : 20;
			$ch = curl_init();
			/*支持SSL 不验证CA根验证*/
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			/*重定向跟随*/
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			if (!empty($timeout)) {
				curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			} else {
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			}
			//设置 CURLINFO_HEADER_OUT 选项之后 curl_getinfo 函数返回的数组将包含 cURL
			//请求的 header 信息。而要看到回应的 header 信息可以在 curl_setopt 中设置
			//CURLOPT_HEADER 选项为 true
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLINFO_HEADER_OUT, false);
			//fail the request if the HTTP code returned is equal to or larger than 400
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			$header = array("Content-Type:application/json;charset=utf-8;", "Connection: keep-alive;","X-ML-Session-Token:".$data["token"],"X-ML-AppId:".$data["appid"]);
			unset($data["token"]);
			unset($data["appid"]);
			$methodIgnoredCase = strtolower($method);
			switch ($methodIgnoredCase) {
				case "post":
					curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //POST数据
					curl_setopt($ch, CURLOPT_URL, $url);
					break;
				case "get":
					curl_setopt($ch, CURLOPT_URL, $url."?para=".urlencode(json_encode($data)));
					break;
				default:
					throw new Exception(getErrMsg()['not_support_way']);
			}
			$result = curl_exec($ch);
			if (curl_errno($ch) > 0) {
				throw new Exception(curl_error($ch));
			}
			curl_close($ch);
			return $result;
		} catch (Exception $e) {
			return "CURL EXCEPTION:".$e->getMessage();
		}
	}
}


class MLPayApi {
	
	const URI_BILL = "/2.0/maxpay/bill";
	const URI_RECORD = "/2.0/maxpay/records";
	/*
	const URI_REFUND
	const URI_BILLS
	const URI_REFUNDS
	const URI_REFUND_STATUS
	const URI_BILL_STATUS
	const URI_TRANSFERS
	const URI_TRANSFER
	*/
	static final private function baseParamCheck(array $data) {
		if (!isset($data["appid"])) {
			throw new Exception(getErrMsg()['need_param'] . "appid");
		}
		if (!isset($data["token"])) {
			throw new Exception(getErrMsg()['need_param'] . "token");
		}
	}
	static final protected function post($api, $data, $timeout) {
		$url = MLRESTUtil::getApiUrl() . $api;
		$httpResultStr = MLRESTUtil::request($url, "post", $data, $timeout);
		$result = json_decode($httpResultStr);
		if (!$result) {
			//throw new Exception(MLRESTErrMsg::UNEXPECTED_RESULT . $httpResultStr);
			throw new Exception(getErrMsg()['unexpected_result'] . $httpResultStr);
		}
		return $result;
	}
	static final protected function get($api, $data, $timeout) {
		$url = MLRESTUtil::getApiUrl() . $api;
		$httpResultStr = MLRESTUtil::request($url, "get", $data, $timeout);
		$result = json_decode($httpResultStr);
		if (!$result) {
			throw new Exception(MLRESTErrMsg::UNEXPECTED_RESULT . $httpResultStr);
		}
		return $result;
	}
	/**
	 * @param array $data
	 * @return mixed
	 * @throws Exception
	 */
	static final public function bill(array $data) {
		//param validation
		self::baseParamCheck($data);
		if (!isset($data["totalFee"])) {
			//throw new Exception(MLRESTErrMsg::NEED_PARAM . "total_fee");
			throw new Exception(getErrMsg()['need_param'] . "total_fee");
		} else if(!is_int($data["totalFee"]) || 1>$data["totalFee"]) {
			//throw new Exception(MLRESTErrMsg::NEED_VALID_PARAM . "total_fee");
			throw new Exception(getErrMsg()['need_valid_param'] . "total_fee");
		}
		if (!isset($data["billNum"])) {
			//throw new Exception(MLRESTErrMsg::NEED_PARAM . "billNum");
			throw new Exception(getErrMsg()['need_param'] . "billNum");
		} else if (32 < strlen(isset($data["billNum"]))) {
			//throw new Exception(MLRESTErrMsg::NEED_VALID_PARAM . "billNum");
			throw new Exception(getErrMsg()['need_valid_param']. "billNum");
		}
		if (!isset($data["subject"])) {
			//TODO: 字节数
			//throw new Exception(MLRESTErrMsg::NEED_PARAM . "subject");
			throw new Exception(getErrMsg()['need_param'] . "subject");
		}
		return self::post(self::URI_BILL, $data, 30);
	}
	
	static final public function record(array $data) {
		self::baseParamCheck($data);
		if (!isset($data["billNum"])) {
			//throw new Exception(MLRESTErrMsg::NEED_PARAM . "billNum");
			throw new Exception(getErrMsg()['need_param'] . "billNum");
		} else if (32 < strlen(isset($data["billNum"]))) {
			//throw new Exception(MLRESTErrMsg::NEED_VALID_PARAM . "billNum");
			throw new Exception(getErrMsg()['need_valid_param']. "billNum");
		}
		return self::post(self::URI_RECORD, $data, 30);
	}
	
}
?>
