<?php
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;



function browsemenu($boti, $eventi, $target,  $pagei) { 

$jiritudo = $target;   //  A B C D が入っている
    
       $msgB = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("自立度 ${jiritudo}");
       
       
       $actions = array(
         new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("相談", "action=browse&target=${jiritudo}&kind=1&menu=servicemenu&page=2"),
                new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("権利擁護", "action=browse&target=${jiritudo}&kind=2&menu=servicemenu&page=2"),
                       new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("社会参加・仲間づくり支援", "action=browse&target=${jiritudo}&kind=3&menu=servicemenu&page=2"),
             new \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder("役割支援",  "action=browse&target=${jiritudo}&kind=4&menu=servicemenu&page=2")

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
$tgm1 = "自立度${jiritudo}向け サービス・支援検索 その2";
$tgm1 = "自立度${jiritudo}向け サービス・支援検索 その3";
 
$img_url = "https://otasukebot.herokuapp.com/otasuke.png";
$button = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度${jiritudo}", $tgm1 , $img_url, $actions);
$msg = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度${jiritudo}", $button);
$button2 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度${jiritudo}", $tgm2 , $img_url, $actions2);
$msg2 = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度${jiritudo}", $button2);     


$button3 = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder("自立度${jiritudo}", $tgm3 , $img_url, $actions3);
$msg3 = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("自立度${jiritudo}", $button2);     
       
       
       
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
