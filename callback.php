<?php
require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LineMessageAPIChannelAccessToken'));

$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LineMessageAPIChannelSecret')]);

$sign = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

$events = $bot->parseEventRequest(file_get_contents('php://input'), $sign);

$page = 1;
$action ="";

foreach ($events as $event) {

   if (!($event instanceof \LINE\LINEBot\Event\MessageEvent) ||
      !($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
      
      if (!($event instanceof \LINE\LINEBot\Event\PostbackEvent) ) {
             continue;
      }
      //  post back event の時の処理
   
      
       $query = $event->getPostbackData();
     
        continue;
      
  }

  firstmessage( $bot, $event, $page );
 //  confirmmessage( $bot, $event, $page );
    
}

function confirmmessage( $boti, $eventi, $pagen )
{

// 「はい」ボタン
$yes_post = new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("はい", "page={$pagen}");
// 「いいえ」ボタン
$no_post = new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("いいえ", "page=-1");
// Confirmテンプレートを作る
$confirm = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder("親御さんについてのお困りごとですか?", [$yes_post, $no_post]);
// Confirmメッセージを作る
$confirm_message = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("メッセージのタイトル", $confirm);

$message = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();

$message->add($confirm_message);
// リプライTokenを付与して返信する
$res = $boti->replyMessage($eventi->getReplyToken(), $message);
}


function firstmessage( $boti, $eventi, $pagen )
{

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("親", "action=select&target=parent"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("子供", "action=select&target=child"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("自分", "action=select&target=self"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("キャンセル", "action=cancel")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け","どなたについてのお困りごとですか？", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("どなたについてのお困りごとですか？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);




  //$bot->replyText($event->getReplyToken(), $event->getText());
}

?>
