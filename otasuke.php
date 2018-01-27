<?php

$accessToken = 'CyQ8Ia7ukcEI9yct3NgYg8jzfyzN5hFQZx4Bq6TASr1jG6OKwQJcIdfV+ZWH0PSPbCVA6u1gM95paHuf6dKsXUikc+biVe/9rnCb+tN4wA6r1VDNqd1WNUinPgBzEX1AVA10eNojxFnOKTNJZABeqAdB04t89/1O/w1cDnyilFU=';


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
	exit;
}

//返信データ作成
$response_format_text = [
	"type" => "text",
	"text" => "お助けボットデース！"
	];
$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
	];

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);
