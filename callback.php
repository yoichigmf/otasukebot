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

                nextmenu( $bot, $event, $data["target"] );
                  continue;
                     }
                 
                         
                 if ( strcmp($action, "target" )==0  ) {
                       $menus = $data["menu"] ;
                       
                      
                       if ( strcmp( $menus , "nintisyomenu" )==0  ) {
                       
                       nintisyomenu( $bot, $event, $query, $page);
                        continue;
                       
                       
                       }
                       
                       if ( strcmp( $menus , "hantei" )==0  ) {
                       
                       hanteimenu( $bot, $event, $query, $page);
                        continue;
                       
                       
                       }
                       
                      if ( strcmp( $menus , "topmenu" )==0  ) {
                       
                      firstmessage( $bot, $event,$page);
                        continue;
                       
                       
                       }

                     }    
                 
                 
                }
           
   
             
             
           }
     
       $bot->replyText($event->getReplyToken(), $query);

         
        continue;
      
  }

  firstmessage( $bot, $event, $page );
 //  confirmmessage( $bot, $event, $page );
    
}

function  notsupport( $boti, $eventi, $targeti )
{

   //    $boti->replyText($eventi->getReplyToken(), $targeti);
       
       
$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症関係?", "action=select&target=nintisyou"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("生活関係？", "action=select&target=seikatu"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("教育関係？", "action=select&target=kyouiku"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("障がい者福祉関係？", "action=syougai")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け","その相談はまだサポートしてません\n困りごとの種類は？", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("まだサポートしていません \n困りごとの種類は？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);


}



function  hanteimenu( $boti, $eventi, $targeti )
{

   //    $boti->replyText($eventi->getReplyToken(), $targeti);
       
       
$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症きづきチェック", "action=select&menu=nintisyomenu&page=0"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知機能・自立度振り分けチャート", "action=select&target=jiritudo&page=0"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("最初のメニュー", "action=select&menu=topmenu"),
   
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け","チェックを選べます", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("困りごとの種類は？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);


}


function nextmenu( $boti, $eventi, $targeti )
{

 if ( strcmp($targeti, "nintisyou" )==0  ) {

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症って何？", "action=target&target=${targeti}&menu=nintisyo_nani&page=0"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症チェック\n自立度判定", "action=target&target=${targeti}&menu=hantei"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("身近な地域で予防活動を", "action=target&target=${targeti}&menu=byouki"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("どこに相談すれば？", "action=target")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け","どのようなお困りごとですか？", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("どのようなお困りごとですか？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

}

else  {
  notsupport( $boti, $eventi, $targeti );
}

}

function nintisyomenu( $boti, $eventi, $targeti, $pagei )
{
parse_str($targetl, $datal);
        
$otarget = $datal["target"];
        
$msgs = array(
"財布や鍵など,物を置いた場所がわからなくなることがありますか",
"5分前に聞いた話を思い出せないことがありますか",
"周りの人から「いつも同じ事を聞く」など物忘れがあると言われますか",
"今日が何月何日かわからないときがありますか",
"言おうとしている言葉が、すぐに出てこないことがありますか",
"貯金の出し入れや、家賃や公共料金の支払いは一人でできますか",
"一人で買い物にいけますか",
"バスや電車、自家用車などを使って一人で外出できますか",
"自分で掃除機やほうきを使って掃除ができますか",
"電話番号を調べて、電話をかけることができますか"

);

$tgm = $msgs[ $pagei];

$npage = $pagei + 1;

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("全くない", "action=target&target=${otarget}&menu=ninchisyo&page=${npage}"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("ときどきある",  "action=target&target=${otarget}&menu=ninchisyo&page=${npage}"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("頻繁にある",  "action=target&target=${otarget}&menu=ninchisyo&page=${npage}"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("いつもそうだ",  "action=target&target=${otarget}&menu=ninchisyo&page=${npage}")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("認知症気づきチェック", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("認知症気づきチェック", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

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
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症関係?", "action=select&target=nintisyou"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("生活関係？", "action=select&target=seikatu"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("教育関係？", "action=select&target=kyouiku"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("障がい者福祉関係？", "action=select")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("お悩み困りごとお助け","困りごとの種類は？", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("困りごとの種類は？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);




}

?>
