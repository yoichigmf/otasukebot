<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/vendor/autoload.php';


use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$log = new Logger('name');
$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));


$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LineMessageAPIChannelAccessToken'));

$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LineMessageAPIChannelSecret')]);

$sign = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

$events = $bot->parseEventRequest(file_get_contents('php://input'), $sign);

$page = 1;
$action ="";

$score = -1;
require "menus.php"; //menus.phpのプログラムを使うよ



foreach ($events as $event) {

   if ($event instanceof \LINE\LINEBot\Event\JoinEvent) {  // Join event add
   
    
   // $log->addWarning("join event!\n");

       firstmessage( $bot, $event,0);
       continue;
   
   }
    
   // $log->addWarning("not join event \n");
   
   if (!($event instanceof \LINE\LINEBot\Event\MessageEvent) ||
      !($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
      
      if (!($event instanceof \LINE\LINEBot\Event\PostbackEvent) ) {
      
     
             continue;
      }
      //  post back event の時の処理
   
      
       $query = $event->getPostbackData();
       
         $log->addWarning("query ${query}\n");
       
         if ($query) {
        // Querystringをパースして配列に戻す
           parse_str($query, $data);
           

           if (isset($data["page"])) {
            $page = $data["page"];
             }
           
           if (isset($data["score"])) {
            $score = $data["score"];
             }
             
            if (isset($data["action"])) {
               $action = $data["action"];
               
               
                 if ( strcmp($action, "select" )==0  ) {    //  menu 選択の場合
                 
                       $menus = $data["menu"] ;
                      
                    if ( strcmp( $menus , "nextmenu" )==0  ) {
                     		nextmenu( $bot, $event, $data["target"] );
                		  continue;
						}
                
                       
                      
                       if ( strcmp( $menus , "nintisyomenu" )==0  ) {
                       
                       nintisyomenu( $bot, $event, $query, $page, 0);
                        continue;
                       
                       
                       }
                       
                      if ( strcmp( $menus , "jiritudomenu" )==0  ) { //  自立度判定
                          jiritudomenu( $bot, $event,  0 , $score );
   
                        continue;
                       }
                       
                       
                       if ( strcmp( $menus , "hantei" )==0  ) {
                       
                       $defmsg = "チェックをしてみましょう";
                       hanteimenu( $bot, $event,  $defmsg, 0);
                        continue;
                       
                       
                       }
                       
                      if ( strcmp( $menus , "topmenu" )==0  ) {
                       
                      firstmessage( $bot, $event,$page);
                        continue;
                       }
                       
                       

                     }    
                     
                     
                 if ( strcmp($action, "target" )==0  ) {    //  連続作業の場合
                           $menus = $data["menu"] ;
                       
                       if ( strcmp( $menus , "nintisyomenu" )==0  ) {
                       
                         $score = $data["score"] ;
                       
                       nintisyomenu( $bot, $event, $query, $page, $score);
                        continue;
                       
                       
                       }   // menus == nintisyomenu
                       
                       
                       if ( strcmp( $menus , "jiritudomenu" )==0  ) {
                       
                         $score = $data["score"] ;
                       
                       jiritudomenu( $bot, $event, $page, $score);
                        continue;
                       
                       
                       }   // menus == jiritudomenu
                       
                       
                       
                       
                       
                 
                     }  // action == target
                     
                 
                  if ( strcmp($action, "browse" )==0  ) {    //  browse サービス・支援検索 
                  
                    //  if ( $page > 1 ) {
                         // $bot->replyText($event->getReplyToken(), $query);
                  
                    //    }
                        
                        
                        if (isset($data["target"])) {
          					  $tg = $data["target"];
            			 }
            			else {
            			  $tg ="A";
            			}
             
                    
                      browsemenu($bot, $event, $tg,  $page);
                      
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





function jiritudomenu( $boti, $eventi,  $pagei , $score ){
$tgm = "";


if ($pagei == 0 ) {  //  first page

$tgm = "日常生活に支障をきたすような症状・行動がありますか？";

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("はい", "action=target&menu=jiritudomenu&page=1&score=${score}"),

       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("いいえ",  "action=target&menu=jiritudomenu&page=2&score=${score}")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度振り分けチャート", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度振り分けチャート", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

return;
       
       }   //  page == 0
       
  if ($pagei == 1 ) {  //  日常生活に支障  程度

$tgm = "支障の程度はどのくらいですか？";

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("多少ある", "action=target&menu=jiritudomenu&page=10&score=${score}"),

   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("時々ある",  "action=target&menu=jiritudomenu&page=11&score=${score}"),
   
   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("頻繁にある",  "action=target&menu=jiritudomenu&page=12&score=${score}")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度振り分けチャート", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度振り分けチャート", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

return;
       
       }    //  page == 1
       
    if ($pagei == 2 ) {  //  日常生活に支障  がない

    if ( $score >= 20 ) {   //  気づきチェックリスト 20点以上  自立度 B
    
       jiritudoBMenu($boti, $eventi,  0 );     //  自立度B メニュー
   
        return;
    }
    
    if ( $score >= 10 ) { //  気づきチェックリスト 20点未満
    
    $tgm = "物忘れが気になりますか?";

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("はい", "action=target&menu=jiritudomenu&page=30&score=${score}"),

   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("いいえ",  "action=target&menu=jiritudomenu&page=31&score=${score}"),
   
  
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度振り分けチャート", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度振り分けチャート", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

return;

    }
    
    //  気づきチェックリストからはきていないかやっていない
    
$tgm = "認知症気付きチェックリストの合計点は20点以上でしたか？";

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("20点以上", "action=target&menu=jiritudomenu&page=20&score=${score}"),

   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("20点未満",  "action=target&menu=jiritudomenu&page=21&score=${score}"),
   
   new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("気付きチェックリストをやる",  "action=select&menu=nintisyomenu&page=0")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度振り分けチャート", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度振り分けチャート", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

return;
       
       }  // page == 2    
       
       
      if ($pagei == 21 ) {  //  介護予防
  
        
         $msgstr = "介護予防  ${pagei}";
       
        $boti->replyText($eventi->getReplyToken(), $msgstr );
        return;
        }
       if ($pagei == 20 ) {  //  自立度A
  
        
         $msgstr = "自立度A  ${pagei}";
       
           jiritudoAMenu($boti, $eventi,  0 );     //  自立度A メニュー
        //$boti->replyText($eventi->getReplyToken(), $msgstr );
        return;
        }
        
           
        
        
   if ($pagei == 10 ) {  //  自立度C
  
        
         $msgstr = "自立度C  ${pagei}";
         
         
          jiritudoCMenu($boti, $eventi,  0 );     //  自立度C メニュー
       
        //$boti->replyText($eventi->getReplyToken(), $msgstr );
        return;
        }
        
    if ($pagei == 11 ) {  //  自立度D
        
         $msgstr = "自立度D  ${pagei}";
       
       jiritudoDMenu($boti, $eventi,  0 );     //  自立度D メニュー
       // $boti->replyText($eventi->getReplyToken(), $msgstr );
        return;
        }    
        
    if ($pagei == 12 ) {  //  自立度E
        
         $msgstr = "自立度E  ${pagei}";
       
        jiritudoEMenu($boti, $eventi,  0 );     //  自立度E メニュー
       // $boti->replyText($eventi->getReplyToken(), $msgstr );
        return;
        }      
           
        
  if ($pagei == 30 ) {  //  自立度A
        
         $msgstr = "自立度A  ${pagei}";
       
        jiritudoAMenu($boti, $eventi,  0 );     //  自立度A メニュー
      //  $boti->replyText($eventi->getReplyToken(), $msgstr );
        return;
        }
        
  if ($pagei == 31 ) {  //  介護予防へ
  
        $msgstr = "介護予防  ${pagei}";
       
        $boti->replyText($eventi->getReplyToken(), $msgstr );
        
        return;
        }
       
       
  $msgstr = "自立度判定 page  ${pagei}";
       
 $boti->replyText($eventi->getReplyToken(), $msgstr );

}  



function nintisyomenu( $boti, $eventi, $targeti, $pagei , $score )
{




if ( $pagei > 9 ) {
    $tgmsg = "";
    
  
    if ( intval($score) < 20 ) {

   
       $tgmsg ="認知症気づきチェックの点数は ${score} 点です  認知症の可能性は少ないです";
       }
     else {
       $tgmsg ="認知症気づきチェックの点数は ${score} 点です 認知機能や社会生活に支障が出ている可能性があります ";
     
     }
  
  
    hanteimenu( $boti, $eventi,  $tgmsg, $score );
    return;
    
    
   //  $boti->replyText($eventi->getReplyToken(), $tgmsg);

}


        
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


$msgar1 = array( "全くない", "ときどきある", "頻繁にある", "いつもそうだ" );

$msgar2 = array( "問題なくできる", "だいたいできる", "あまりできない", "できない" );
$msg0 = "";
$msg1 = "";
$msg2 = "";
$msg3 = "";

if ( $pagei > 4 ) {
$msg0 = $msgar2[0];
$msg1 = $msgar2[1];
$msg2 = $msgar2[2];
$msg3 = $msgar2[3];

}
else {
$msg0 = $msgar1[0];
$msg1 = $msgar1[1];
$msg2 = $msgar1[2];
$msg3 = $msgar1[3];
}

$score1 = $score + 1;
$score2 = $score + 2;
$score3 = $score + 3;
$score4 = $score + 4;

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder($msg0, "action=target&target=${otarget}&menu=nintisyomenu&page=${npage}&score=${score1}"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder($msg1,  "action=target&target=${otarget}&menu=nintisyomenu&page=${npage}&score=${score2}"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder($msg2,  "action=target&target=${otarget}&menu=nintisyomenu&page=${npage}&score=${score3}"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder($msg3,  "action=target&target=${otarget}&menu=nintisyomenu&page=${npage}&score=${score4}")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("認知症気づきチェック", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("認知症気づきチェック", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

}













?>
