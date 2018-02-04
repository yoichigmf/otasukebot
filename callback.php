<?php
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LineMessageAPIChannelAccessToken'));

$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LineMessageAPIChannelSecret')]);

$sign = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

$events = $bot->parseEventRequest(file_get_contents('php://input'), $sign);

$page = 1;

foreach ($events as $event) {

   if (!($event instanceof \LINE\LINEBot\Event\MessageEvent) ||
      !($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
      
      if (!($event instanceof \LINE\LINEBot\Event\PostbackEvent) ) {
             continue;
      }
      
      bot->replyText($event->getReplyToken(), $page);
}
      continue;
      
  }

// 「はい」ボタン
$yes_post = new PostbackTemplateActionBuilder("はい", "page={$page}");
// 「いいえ」ボタン
$no_post = new PostbackTemplateActionBuilder("いいえ", "page=-1");
// Confirmテンプレートを作る
$confirm = new ConfirmTemplateBuilder("メッセージ", [$yes_post, $no_post]);
// Confirmメッセージを作る
$confirm_message = new TemplateMessageBuilder("メッセージのタイトル", $confirm);

$message = new MultiMessageBuilder();

$message->add($confirm_message);
// リプライTokenを付与して返信する
$res = $bot->replyMessage($event->getReplyToken(), $message);


  //$bot->replyText($event->getReplyToken(), $event->getText());
}
