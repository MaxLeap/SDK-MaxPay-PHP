A php class for pay with the name is MLPay.php
A php test file show how to use it with the name is testMLpay.php

MLPay.php 使用方法
一、支付
1. require "MLPay.php";
2. 填充数组 $data ，内容包括
   必须: appid: 由MaxLeap 后台获取
         token: 由MaxLeap 后台获取
         billNum: 订单号，需要保证唯一，由客户端提供，需请自行确保在商户系统中唯一
         channel: 支付渠道, 目前支持 ali_web
         totalFee: 整数,单位为分
         subject: 订单主题
    可选:
         extras: 附加数据, 类型为php数组
         returnUrl: 同步自动跳转url
3. 静态调用 $result = MLPayApi::bill($data);
4. 返回值包含在$result中

二、订单查询
1. require "MLPay.php";
2. 填充数组$data, 内容包括
   必须: appid: 由MaxLeap 后台获取
         token: 由MaxLeap 后台获取
         billNum: 订单号
3. 静态调用 $result = MLPayApi::record($data);
4. 返回值包含在$result中
  