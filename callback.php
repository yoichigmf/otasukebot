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
       
         if ($query) {
        // Querystringをパースして配列に戻す
           parse_str($query, $data);
           
           if (isset($data["page"])) {
            $page = $data["page"];
             }
           
            if (isset($data["action"])) {
               $action = $data["action"];
               
               
                 if ( strcmp($action, "select" )==0  ) {

             //confirmmessage( $bot, $event,$data["target"]);
                nextmenu( $bot, $event, $data["target"] );
                  continue;
                     }
                 
                         
                 if ( strcmp($action, "select2" )==0  ) {

           //  confirmmessage( $bot, $event,$data["target"]);
            //     nextmessage( $bot, $event, $data["target"] );
                 
                   firstmessage( $bot, $event, $page );
                  continue;
                     }    
                 
                 
                }
           
   
             
             
           }
     
       $bot->replyText($event->getReplyToken(), $query);

         
        continue;
      
  }

  firstmessage( $bot, $event, $page );
 //  confirmmessage( $bot, $event, $page );
    
}



function nextmenu( $boti, $eventi, $targeti )
{

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症かもしれない", "action=target&target=${targeti}&menu=ninchisyo&page=0"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("介護について", "action=target&target=${targeti}"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("病気・けが", "action=targettarget=${targeti}"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("キャンセル", "action=cancel")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け","どのようなお困りごとですか？", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("どのようなお困りごとですか？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);




}




function nextmessage( $boti2, $eventi, $targeti )
{

      // $boti->replyText($eventi->getReplyToken(), "${targeti} in the target menu" );
$actions2 = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症かもしれない", "action=target&target=${targeti}&menu=ninchisyo"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("介護について", "action=target&target=${targeti}&menu=kaigo"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("病気・けが", "action=target&target=${targeti}&menu=sick"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("生活", "action=target&target=${targeti}&menu=life"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("キャンセル", "action=cancel")
);
 
 

$img_url2 = "https://otasukebot.herokuapp.com/otasuke.png";
$button2 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け", "どのようなお困りごとですか？", $img_url2, $actions2);
$msg2 = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("どのようなお困りごとですか？", $button2);

// $boti->replyText($eventi->getReplyToken(), "${targeti} in the target menu 3" );
 $res = $bot2i->replyMessage($eventi->getReplyToken(),$msg2);


//$boti->replyText($eventi->getReplyToken(), "${targeti} in the target menu 3 ${res}" );

}



function confirmmessage( $boti, $eventi, $pagen )
{

// 「はい」ボタン
$yes_post = new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("はい", "action=select2&target={$pagen}");
// 「いいえ」ボタン
$no_post = new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("いいえ", "action=cancel");
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




}

?>
