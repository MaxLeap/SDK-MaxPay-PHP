A php class for pay with the name is MLPay.php
A php test file show how to use it with the name is testMLpay.php

MLPay.php 使用方法
一、支付
1. require "MLPay.php";
2. 填充数组 $data ，内容包括
   必须: appid: 由MaxLeap 后台获取,类型:String
         token: 由MaxLeap 后台获取,类型:String
         billNum: 订单号，需要保证唯一，由客户端提供，需请自行确保在商户系统中唯一,类型:String
         channel: 支付渠道, 目前支持 ali_web,类型:String
         totalFee: 整数,单位为分,类型:Integer
         subject: 订单主题,类型:String
    可选:
         extras: 附加数据, 类型:Array
         returnUrl: 同步自动跳转url类型:String
3. 静态调用 $result = MLPayApi::bill($data);
4. 返回值包含在$result中,结构如下:
    {
        code:0,
	 msg:"OK",
        err:"",
        id:"",
        ali_app:"",
        ali_web:""
     }
     说明:
      code: 类型: Integer; 含义:返回码，0为正常
      msg: 类型: String; 含义: 返回信息， OK为正常
      err: 类型: String; 含义: 具体错误信息
      id: 类型: String; 含义: 成功发起支付后返回支付表记录唯一标识
      返回code 定义:      
           0 | OK | 成功
           1 | APP_INVALID | 根据app_id找不到对应的APP或者app_sign不正确
           2 | PAY_FACTOR_NOT_SET | 支付要素在后台没有设置
           3 | CHANNEL_INVALID | channel参数不合法
           4 | MISS_PARAM | 缺少必填参数
           5 | PARAM_INVALID | 参数不合法
           6 | CERT_FILE_ERROR | 证书错误
           7 | CHANNEL_ERROR | 渠道内部错误
           14 | RUN_TIME_ERROR | 实时未知错误，请与技术联系帮助查看
	

二、订单查询
1. require "MLPay.php";
2. 填充数组$data, 内容包括
   必须: appid: 由MaxLeap 后台获取
         token: 由MaxLeap 后台获取
         billNum: 订单号
3. 静态调用 $result = MLPayApi::record($data);
4. 返回值包含在$result中