<?php
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LineMessageAPIChannelAccessToken'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LineMessageAPIChannelSecret')]);
$sign = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
$events = $bot->parseEventRequest(file_get_contents('php://input'), $sign);

foreach ($events as $event) {

  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent) || !($event instanceof \LINE\LINEBot\Event\MessageEven\PostbackEvent)) {
        continue;
    }
    

  $bot->replyText($event->getReplyToken(), $event->getText());
}
