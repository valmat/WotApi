<?php
/**
  *   Web of Trust (WOT) API PHP - wrapper
  *   License BSD
  *   Вы можете делать с моим кодом что угодно, но я ни за что ответственности не несу. Используйте на свой страх и риск.
  *   You can do anything with my code, but I'm for anything not responsible. Use at your own risk.
  *   Copyright (c) 2014, Valeriy Dmitriev aka Valmat
  *   @github:  https://github.com/valmat/WotApi
  *   Sponsored by 1gs.ru
  */

  
  
/**
 * This class is used to obtain information about reputation and confidence
 * class WotReputation
 */
class WotReputation
{
    /**
     * reputation
     * @var int
     */
    private $_rep;
    
    /**
     * confidence
     * @var int
     */
    private $_con;
    
    /**
     * minimal confidence
     * @var int
     */
    private $_min_con = 8;
    
    /**
     * reputation supremum
     * @var int
     */
    private $_rep_sup = NULL;
    
    /**
     * constructor
     * @param int reputation
     * @param int confidence
     */
    public function __construct($rep, $con) {
        $this->_rep = (int)$rep;
        $this->_con = (int)$con;
    }
        
    /**
     * @return string reputation
     */
    public function reputation() {
        return $this->_rep;
    }
        
    /**
     * @return int confidence
     */
    public function confidence() {
        return $this->_con;
    }
        
    /**
     * @return string description
     */
    public function description() {
        return ($this->repSupremum() < 0) ?
                WotDescriptions::REP_DESCR_UNDEF :
                WotDescriptions::init()->repDescription[$this->_rep_sup];
    }
        
    /**
     * @return string ico file name
     */
    public function ico() {
        return ($this->repSupremum() < 0) ?
                WotDescriptions::REP_ICO_UNDEF :
                WotDescriptions::init()->repIco[$this->_rep_sup];
    }
        
    /**
     * @return string ico integer index
     */
    public function icoIndex() {
        return $this->repSupremum() + 1;
    }
        
    /**
     * cast to a string
     * @return string reputation
     */
    public function __toString() {
        return (string)$this->_rep;
    }
        
    /**
     * set minimal confidence
     * @return string reputation
     */
    public function minConfidence($min_con) {
        $this->_min_con = $min_con;
    }
        
    /**
     * @return int reputation supremum
     */
    private function repSupremum() {
        if(NULL !== $this->_rep_sup) {
            return $this->_rep_sup;
        }
        if($this->_con < $this->_min_con) {
            return ($this->_rep_sup = -1);
        }
        $sup = WotDescriptions::init()->repSupremums;
        end($sup);
        while ( NULL !== ( $ind = key($sup) ) ) {
            if($this->_rep >= current($sup)) {
                $this->_rep_sup = $ind;
                return $ind;
            }
            prev($sup);
        }
    }
}

/**
 * class WotCategory
 */
class WotCategory
{
    /**
     * categorie nomber
     * @var int
     */
    private $cat;
    
    /**
     * reputation
     * @var int
     */
    private $rep;
        
    /**
     * constructor
     * @param int categorie nomber
     * @param int reputation
     */
    public function __construct($cat, $rep) {
        $this->cat = (int)$cat;
        $this->rep = (int)$rep;
    }
    
    public function reputation() {
        return $this->rep;
    }
    
    /**
     * @return string group
     */
    public function group() {
        return WotDescriptions::init()->group($this->cat);
    }
    
    /**
     * @return string descriptions
     */
    public function description() {
        return WotDescriptions::init()->description($this->cat);
    }
}

/**
 * class CategoryIterator
 */
class CategoryIterator implements Iterator
{
    /**
     * catigories array
     * @var stdClass
     */
    private $cArr;
        
    /**
     * constructor
     * @param stdClass catigories array
     */
    public function __construct(stdClass &$arr) {
        $this->cArr = $arr;
    }
    
    /**
     * Iterator implementation
     */
    function rewind() {
        reset($this->cArr);
    }
    function current() {
        return new WotCategory(key($this->cArr), current($this->cArr));
    }
    function key() {
        return key($this->cArr);
    }
    function next() {
        next($this->cArr);
    }
    function valid() {
        return key($this->cArr) !== NULL;
    }
}

/**
 * class WotBlackLists
 */
class WotBlackLists
{
    /**
     * black list type
     * @var string
     */
    private $_type;
    
    /**
     * black list tymestamp
     * @var long
     */
    private $_time;
        
    /**
     * constructor
     * @param string black list type
     * @param long black list tymestamp
     */
    public function __construct($type, $time) {
        $this->_type = $type;
        $this->_time = $time;
    }
        
    /**
      *  @return string type
      */
    function type() {
        return $this->_type;
    }
        
    /**
      *  @return string description
      */
    function description() {
        return WotDescriptions::init()->blacklist($this->_type);;
    }
        
    /**
      *  @return long black list tymestamp
      */
    function time() {
        return $this->_time;
    }

}


/**
 * class WotObj
 */
class WotObj implements IteratorAggregate
{
    /**
     * raw Wot array
     * @var stdClass
     */
    private $arr;
        
    /**
     * lang signature
     * @var string
     */
    public $lang = 'en';
        
    /**
     * trustWorthiness
     * @var WotReputation
     */
    private $_trustWorthiness = NULL;
        
    /**
     * trustWorthiness
     * @var WotReputation
     */
    private $_childSafety = NULL;
        
    /**
     * constructor
     * @param stdClass  raw Wot array
     */
    public function __construct(stdClass &$arr) {
        $this->arr = $arr;
    }
        
    /**
      *  TrustWorthiness
      *  How much do you trust this site?
      *  Заслуживает доверия
      *  Насколько я доверяю этому сайту?
      */
    public function trustWorthiness() {
        if($this->_trustWorthiness) {
            return $this->_trustWorthiness;
        }
        return isset($this->arr->{0}) ?
                    ( $this->_trustWorthiness = new WotReputation($this->arr->{0}[0], $this->arr->{0}[1]) ) :
                    ( $this->_trustWorthiness = new WotReputation(0, 0) );
    }
        
    /**
      *  Child safety
      *  How suitable is this site for children?
      *  Безопасность для детей
      *  Насколько этот сайт подходит для детей?
      */
    public function childSafety() {
        if($this->_childSafety) {
            return $this->_childSafety;
        }
        return isset($this->arr->{4}) ?
                    ( $this->_childSafety = new WotReputation($this->arr->{4}[0], $this->arr->{4}[1]) ) :
                    ( $this->_childSafety = new WotReputation(0, 0) );
    }
        
    /**
      *  @return string target
      */
    public function target() {
        return $this->arr->target;
    }
        
    /**
      *  @return string url to wot raiting
      */
    public function url() {
        return 'https://www.mywot.com/' . $this->lang . '/scorecard/' . $this->arr->target;
    }
        
    /**
      *  @return array black lists
      */
    public function blacklists() {
        if(!isset($this->arr->blacklists)) {
            return NULL;
        }
        $ret = array();
        foreach($this->arr->blacklists as $type => $time ) {
            $ret[$type] = new WotBlackLists($type, $time);
        }
        return $ret;
    }
        
    /**
      *  categories iterator 
      *  @return Iterator
      */
    public function getIterator() {
        return isset($this->arr->categories) ?
                new CategoryIterator($this->arr->categories) :
                new CategoryIterator(new stdClass);
    }
}

/**
 * class WotList
 */
class WotList implements Iterator, Arrayaccess, Countable
{
    /**
     * API key
     * @var string
     */
    private $_list;
    
        
    /**
     * constructor
     * @param string  $json
     */
    public function __construct($json) {
        $this->_list = json_decode($json);
    }
        
    /**
      *  implements Arrayaccess 
      */
    public function offsetExists($offset) {
        return isset($this->_list->{$offset});
    }
    public function offsetUnset($offset) {
        unset($this->_list->{$offset});
    }
    public function offsetGet($offset) {
        return isset($this->_list->{$offset}) ? ( new WotObj($this->_list->{$offset}) ) : NULL;
    }
    // not supported
    public function offsetSet($offset, $value) {}
        
    /**
      *  implements Iterator 
      */
    function current() {
        return new WotObj(current($this->_list));
    }
    function key() {
        return key($this->_list);
    }
    function next() {
        next($this->_list);
    }
    function rewind() {
        reset($this->_list);
    }
    function valid() {
        return (NULL !== key($this->_list));
    }
        
    /**
      *  implements Countable 
      */
    public function count() {
        return count( (array)$this->_list );
    } 

}

/**
 * class Wot
 */
class Wot
{
    /**
     * API key
     * @var string
     */
    public $_api;
        
    /**
     * domains string
     * @var string
     */
    public $_domains = '';
        
    /**
     * constructor
     * @param string  $api_key
     * @param array or string $domains
     */
    public function __construct($api_key, $domains = NULL) {
        $this->_api = $api_key;
        if($domains) {
            $this->_domains = is_array($domains) ?
                    implode('/', $domains) :
                    $domains;
        }
    }
        
    /**
     * add domain to retrieve
     * @param string  $api_key
     * @return Wot this
     */
    public function add($domain) {
        $this->_domains .= strlen($this->_domains) ? ('/' . $domain) : $domain;
        return $this;
    }
        
    /**
     * get WotList
     * @return WotList this
     */
    public function get() {
        //return new WotList('{ "ya.ru": { "target": "ya.ru", "0": [ 94, 59 ], "1": [ 94, 59 ], "2": [ 94, 59 ], "4": [ 91, 51 ], "categories": { "501": 98, "304": 4 } }, "google.com": { "target": "google.com", "0": [ 94, 81 ], "1": [ 94, 81 ], "2": [ 94, 81 ], "4": [ 93, 72 ], "categories": { "501": 97, "301": 14 } }, "er.ru": { "target": "er.ru", "0": [ 20, 25 ], "1": [ 20, 25 ], "2": [ 20, 25 ], "4": [ 20, 21 ], "categories": { "102": 20, "104": 14, "303": 13, "204": 13, "201": 12 } }, "crawler.sistrix.net": { "target": "crawler.sistrix.net", "0": [ 9, 12 ], "1": [ 9, 12 ], "2": [ 9, 12 ], "4": [ 8, 10 ], "categories": { "101": 25 } }, "aktau-zan.com": { "target": "aktau-zan.com", "0": [ 26, 8 ], "1": [ 26, 8 ], "2": [ 26, 8 ], "categories": { "101": 4 }, "blacklists": { "malware": 1391655180 } } }');
        
        $url = 'http://api.mywot.com/0.4/public_link_json2?hosts=' . $this->_domains . '/&key=' . $this->_api;
        $json = file_get_contents ( $url);
        return $json ? ( new WotList($json) ) : ( new WotList('{}') );
    }
}