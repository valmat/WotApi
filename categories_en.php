<?php

/**
  *  Categories
  *  In addition to reputations, the rating system also computes categories for websites based on votes from users and third parties.
  *  Category data aims to explain the reason behind a poor reputation,
  *  and you can use the information to more specifically determine what type of action to take when coming across a poorly rated site.
  *  
  *  For each category, the reputation system also computes a confidence value c ∊ {0, ..., 100},
  *  similarly to reputations. The higher the value, the more reliable the category assignment can be considered.
  *  If you use categories to determine the severity of a poor reputation,
  *  you may want to use a lower confidence threshold for the category data. 
  */

class WotDescriptions
{
    
    /**
     * Static variable in which we will store an instance of a class
     * @var WotDescriptions
     */
    private static $_instance = NULL;
 
    private function __construct(){}
    private function __clone(){}
    
    /**
     * @return WotDescriptions
     */
    public static function init() {
        if (NULL === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * @param int category
     * @return string group
     */
    public function group($cat) {
        return isset( $this->_category_group[$cat]) ? $this->_group[ $this->_category_group[$cat]] : NULL;
    }
    
    /**
     * @param int category
     * @return string descriptions
     */
    public function description($cat) {
        return isset( $this->_descriptions[$cat]) ? $this->_descriptions[$cat] : NULL;
    }
    
    /**
     * @param int category
     * @return string blacklist description
     */
    public function blacklist($key) {
        return isset( $this->_blacklist[$key]) ? $this->_blacklist[$key] : NULL;
    }
    
    private $_group = array(
        0 => 'Negative',
        1 => 'Questionable',
        2 => 'Neutral',
        3 => 'Positive',
        4 => 'Negative (child)',
        5 => 'Questionable (child)',
        6 => 'Positive (child)',
    );
    
    
    public $repSupremums = array(0, 20, 40, 60, 80);
    
    public $repDescription = array(
        'Very poor',      // 0
        'Poor',           // 20
        'Unsatisfactory', // 40
        'Good',           // 60
        'Excellent',      // 80
    );
    const REP_DESCR_UNDEF = 'Unknown';
    
    public $repIco = array(
        'verypoor',       // 0
        'poor',           // 20
        'unsatisfactory', // 40
        'good',           // 60
        'excellent',      // 60
    );
    const REP_ICO_UNDEF = 'unknown';
    
    private $_category_group = array(
        //Negative
        101 => 0,   //	Malware or viruses
        102 => 0,   //	Poor customer experience
        103 => 0,   //	Phishing
        104 => 0,   //	Scam
        105 => 0,   //	Potentially illegal
        //Questionable
        201 => 1,   // 	Misleading claims or unethical
        202 => 1,   // 	Privacy risks
        203 => 1,   // 	Suspicious
        204 => 1,   // 	Hate, discrimination
        205 => 1,   // 	Spam
        206 => 1,   // 	Potentially unwanted programs
        207 => 1,   // 	Ads / pop-ups
        //Neutral
        301 => 2,   // 	Online tracking
        302 => 2,   // 	Alternative or controversial medicine
        303 => 2,   // 	Opinions, religion, politics
        304 => 2,   // 	Other
        //Positive
        501 => 3,   // 	Good site
        //The following categories provide additional information about child safety:
        //Negative (child)
        401 => 4,   // 	Adult content
        //Questionable (child)
        402 => 5,   // 	Incidental nudity
        403 => 5,   // 	Gruesome or shocking
        //Positive (child)
        404 => 6,   // 	Site for kids
    );
        
    private $_descriptions = array(
        //Negative
        101 => 'Malware or viruses',
        102 => 'Poor customer experience',
        103 => 'Phishing',
        104 => 'Scam',
        105 => 'Potentially illegal',
        //Questionable
        201 => 'Misleading claims or unethical',
        202 => 'Privacy risks',
        203 => 'Suspicious',
        204 => 'Hate, discrimination',
        205 => 'Spam',
        206 => 'Potentially unwanted programs',
        207 => 'Ads / pop-ups',
        //Neutral
        301 => 'Online tracking',
        302 => 'Alternative or controversial medicine',
        303 => 'Opinions, religion, politics',
        304 => 'Other',
        //Positive
        501 => 'Good site',
        
        //The following categories provide additional information about child safety:
        //Negative (child)
        401 => 'Adult content',
        //Questionable (child)
        402 => 'Incidental nudity',
        403 => 'Gruesome or shocking',
        //Positive (child)
        404 => 'Site for kids',
    );
    
    
    /**
      *   Third-party blacklists
      *   If a website is included in a third-party blacklist and it's possible that this blacklisting affects its reputation,
      *   the API will return information about the type of blacklist the site was found in,
      *   and when the site was last added there. Here are the current blacklist types:
      *   
      *   Note that if a site appears on multiple third-party blacklists of the same type,
      *   the latest time it was added to either one of them will be returned.
      */
    
    //Blacklist type 	Description
    private $_blacklist = array(
        'malware'  => 'Site is blacklisted for hosting malware.',
        'phishing' => 'Site is blacklisted for hosting a phishing page.',
        'scam'     => 'Site is blacklisted for hosting a scam (e.g. a rogue pharmacy).',
        'spam'     => 'Site is blacklisted for sending spam or being advertised in spam.',
    );

}
