<?php
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

//$log2 = new Logger('name');
//$log2->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));






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




function jiritudoDMenu($boti, $eventi,  $pagei) {

    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度D 日常生活に手助け・介護が必要");
       
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("サービス・支援検索", "action=browse&target=D&menu=servicemenu&page=1"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("戻る",  "action=select&menu=topmenu")

);

$tgm = "自立度D用主なサービス・支援の内容を調べますか？";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度D", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度D", $button);

       
       
       $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
       $multiplemsg->add( $msgB )
                           ->add( $msg );
                           
    
        $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
        return;

}



function jiritudoEMenu($boti, $eventi,  $pagei) {

    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度E 常に介護が必要");
       
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("サービス・支援検索", "action=browse&target=E&menu=servicemenu&page=1"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("戻る",  "action=select&menu=topmenu")

);

$tgm = "自立度E用主なサービス・支援の内容を調べますか？";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度E", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度E", $button);

       
       
       $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
       $multiplemsg->add( $msgB )
                           ->add( $msg );
                           
    
        $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
        return;

}




function jiritudoAMenu($boti, $eventi,  $pagei) {

    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度A 軽度認知障害(MCI)・認知症の疑い");
       
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("サービス・支援検索", "action=browse&target=A&menu=servicemenu&page=1"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("戻る",  "action=select&menu=topmenu")

);

$tgm = "自立度A用主なサービス・支援の内容を調べますか？";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度A", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度A", $button);

       
       
       $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
       $multiplemsg->add( $msgB )
                           ->add( $msg );
                           
    
        $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
        return;

}


function jiritudoBMenu($boti, $eventi,  $pagei) {

    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度Bです 認知症の症状はあるが日常生活は自立");
       
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("サービス・支援検索", "action=browse&target=B&menu=servicemenu&page=1"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("戻る",  "action=select&menu=topmenu")

);

$tgm = "自立度Bの方向けの主なサービス・支援の内容を調べますか？";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度B", $tgm , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度B", $button);

       
       
       $multiplemsg = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
       
       $multiplemsg->add( $msgB )
                           ->add( $msg );
                           
    
        $boti->replyMessage($eventi->getReplyToken(), $multiplemsg );
        return;

}





function  hanteimenu( $boti, $eventi, $mnmsg, $tgscore )

{

    //    $boti->replyText($eventi->getReplyToken(), $targeti);
       
       
$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症気づきチェック", "action=select&menu=nintisyomenu&page=0"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知機能・自立度振り分け", "action=select&target=jiritudo&menu=jiritudomenu&score=${tgscore}&target=jiritudo&page=0"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("最初のメニュー", "action=select&menu=topmenu"),
   
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("認知症チェック",$mnmsg, $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("困りごとの種類は？", $button);
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


function nextmenu( $boti, $eventi, $targeti )
{

 if ( strcmp($targeti, "nintisyou" )==0  ) {

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症って何？", "action=select&target=${targeti}&menu=nintisyo_nani&page=0"),
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症チェック\n自立度判定", "action=select&target=${targeti}&menu=hantei&score=-1"),
    new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("身近な地域で予防活動を", "action=target&target=${targeti}&menu=byouki"),
       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("どこに相談すれば？", "action=target")
);
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("認知症情報","認知症についてのお助けメニューです", $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("どのようなお困りごとですか？", $button);
$res = $boti->replyMessage($eventi->getReplyToken(),$msg);

}

else  {
  notsupport( $boti, $eventi, $targeti );
}

}

function firstmessage( $boti, $eventi, $pagen )
{

$actions = array(
  new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("認知症関係?", "action=select&menu=nextmenu&target=nintisyou"),
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
