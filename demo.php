<?php
/*
 WOT API Terms of Service

In addition to the WOT Terms of Service, the following restrictions apply to using the API:

    The API is free for individuals and non-commercial use.
    You must not make more than 25000 API requests during any 24 hour period.
    You must limit your request rate to at most 10 requests per second.

If you are unable to comply with these restrictions, please contact us regarding partnership or commercial offering.
Furthermore, if you use the API in your application, we recommend the following:

    You should credit WOT in your application. You can use our badges, for example.
    You should not request the same information more than once during any 30 minute period, but should use a local cache for repeated requests instead.
*/

/**
  *  Get API key: http://www.mywot.com/profile/api
  *  description: https://www.mywot.com/wiki/API
  *  description(RU): http://wot-russia.blogspot.ru/2013/07/api-wot-20_140.html
  */

$key = '<put your api key here>';
$domains =array(
                'ya.ru',                // good site
                'google.com',           // good site
                'er.ru',                // poor site
                'crawler.sistrix.net',  // poor site
                'aktau-zan.com'         // poor site
                );


require 'categories_en.php';
require 'wotobj.php';


$wot = new Wot($key, $domains);
$wot->add('www.1gs.ru');                // not estimated site
$wot->add('msk.1gs.ru');                // good site
$wotList = $wot->get();

echo "<hr><pre>";
var_export($wotList['aktau-zan.com']);
echo "</pre><hr>";

echo "<hr><pre>";
var_export( count( $wotList ) );
echo "</pre><hr>";




foreach($wotList as $k => $t) {
    $t->lang = 'ru';
    echo "<hr>\n$k : ", $t->target();
    echo "<br>\n", $t->url();
    echo "<br>\n", $t->trustWorthiness(), ' ~~~ ', $t->trustWorthiness()->confidence(), ' ~~~ ', $t->trustWorthiness()->ico(), ' ~~~ ', $t->trustWorthiness()->description(), ' ~~~ ', $t->trustWorthiness()->icoIndex();
    echo "<br>\n", $t->childSafety(), ' ~~~ ', $t->childSafety()->confidence(), ' ~~~ ', $t->childSafety()->ico(), ' ~~~ ', $t->childSafety()->description(), ' ~~~ ', $t->childSafety()->icoIndex();
    
    echo "<br><pre>";
    var_export($t->blacklists());
    echo "</pre>";
    
    foreach($t as $ck => $cv) {
        echo "<br>\n =========", $ck , ' ~~~ ', $cv->reputation(), ' ~~~ ', $cv->group(), ' ~~~ ', $cv->description();
    }
}

