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
             
                        $tgkind = "";
             
                       if (isset($data["kind"])) {
          					  $tgkind = $data["kindt"];
            			 }
            			else {
            			  $tgkind ="";
            			}
            			
            			
                      if ( $page > 1 ) {   //  検索
                        
                              srcmenu($bot, $event, $tg, $kind, $page);
                      
                                 continue;
                    
                        
                        
                        }
                      else {
                             browsemenu($bot, $event, $tg,  $page);
                      
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


function srcmenu($boti, $eventi, $targeti, $kindi,  $pagei) { 

global $log;


$jiritudo = $targeti;   //  A B C D が入っている

$tgurl = "https://script.google.com/macros/s/AKfycbz8Y6MCUMXYc7llYhuyYh5QWT3AOuXR5kwjE-D-YwQdQecSFvQZ/exec?action=getrows&sheetname=${kindi}&column=${targeti}";

$timeout = 20;

$log->addWarning($tgurl);
$response = getApiDataCurl( $tgurl, $timeout );

if ( count($result) > 0 ) {
  $tgr = $response["response"];
  
  
      $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
   //    $multiplemsg->add( $msgB )
   //                        ->add( $msg )
    //                       ->add($msg2 )
                              ->add($msg3 );
                           
    
      
        
        
  if ( count($tgr) > 0 ) {
       foreach($tgr as $value){
         $multiplemsg->add( new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($value));
     // print "\n${value}";
	    }
  
   }
   
     $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
   
}
else  {

$log->addWarning("query error\n");
} 




}


function getApiDataCurl($url, $timeout )
{
   


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );

curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
//最大何回リダイレクトをたどるか
curl_setopt($ch,CURLOPT_MAXREDIRS,10);
//リダイレクトの際にヘッダのRefererを自動的に追加させる
curl_setopt($ch,CURLOPT_AUTOREFERER,true);

$content = trim(curl_exec($ch));
    

    $info    = curl_getinfo($ch);
    $errorNo = curl_errno($ch);
    
    curl_close($ch);
    
    

    //p
    
    
    // OK以外はエラーなので空白配列を返す
    if ($errorNo !== CURLE_OK) {

        return [];
    }

    // 200以外のステータスコードは失敗とみなし空配列を返す
    if ($info['http_code'] !== 200) {
        return [];
    }

    print "\nok\n";
    print "content = ${content}\n";
    

    // 文字列から変換
    $jsonArray = json_decode($content, true);

    return $jsonArray;
}






function browsemenu($boti, $eventi, $targeti,  $pagei) { 

$jiritudo = $targeti;   //  A B C D が入っている

//$log->addWarning("browsemenu  ${jiritudo}\n");
    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度 ${jiritudo}");
       
       //        $boti->replyMessage($eventi->getReplyToken(), $msgB );
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("相談", "action=browse&target=${jiritudo}&kind=相談&menu=servicemenu&page=2"),
                new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("権利擁護", "action=browse&target=${jiritudo}&kind=権利擁護&menu=servicemenu&page=2"),
                       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("社会参加・仲間づくり支援", "action=browse&target=${jiritudo}&kind=社会参加・仲間づくり支援&menu=servicemenu&page=2"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("役割支援",  "action=browse&target=${jiritudo}&kind=役割支援&menu=servicemenu&page=2")

);


   
       $actions2 = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("安否確認・見守り支援", "action=browse&rank=${jiritudo}&target=C&menu=servicemenu&page=2"),
                new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("医療系サービス", "action=browse&rank=${jiritudo}&target=C&menu=servicemenu&page=2"),
                       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("生活支援", "action=browse&rank=${jiritudo}&target=C&menu=servicemenu&page=2"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("身体的ケア",  "action=select&menu=topmenu")

);

       $actions3 = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("家族・介護者支援", "action=browse&rank=${jiritudo}&target=C&menu=servicemenu&page=1"),
                new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("住まい・居住系サービス", "action=browse&rank=${jiritudo}&target=C&menu=servicemenu&page=1"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("戻る",  "action=select&menu=topmenu")

);


$tgm1 = "自立度${jiritudo}向け サービス・支援検索 その1";
$tgm2 = "自立度${jiritudo}向け サービス・支援検索 その2";
$tgm3 = "自立度${jiritudo}向け サービス・支援検索 その3";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度${jiritudo}", $tgm1 , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度${jiritudo}", $button);
$button2 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度${jiritudo}", $tgm2 , $img_url, $actions2);
$msg2 = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度${jiritudo}", $button2);     


$button3 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度${jiritudo}", $tgm3 , $img_url, $actions3);
$msg3 = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度${jiritudo}", $button3);     
       
       
       
       $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
       $multiplemsg->add( $msgB )
                           ->add( $msg )
                           ->add($msg2 )
                              ->add($msg3 );
                           
    
        $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
        return;

}


function jiritudoCMenu($boti, $eventi,  $pagei) {

    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度C 誰かの見守りがあれば日常生活は自立");
       
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("サービス・支援検索", "action=browse&target=C&menu=servicemenu&page=1"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("戻る",  "action=select&menu=topmenu")

);

$tgm = "自立度C用主なサービス・支援の内容を調べますか？";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度C", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度C", $button);

       
       
       $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
       $multiplemsg->add( $msgB )
                           ->add( $msg );
                           
    
        $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
        return;

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
