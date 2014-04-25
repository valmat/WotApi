<?php

/**
  *  В дополнение к репутации, система также вычисляет категорий для сайтов на основе голосов от пользователей и доверенных источников данных.
  *  Категории призваны объяснить причину плохой репутации, и вы можете использовать эту информацию,
  *  чтобы более конкретно определить, тип реакции для сайтов с плохой репутацией.
  *  
  *  Для каждой категории, Система  репутаций также вычисляет значение  уверенности ε {0, ..., 100},
  *  как и для репутации. Чем выше значение, тем выше надежность присвоенной категории.
  *  Если вы используете категории  для определения категории тяжести плохой репутации,
  *  вы можете использовать более низкий порог уверенность для категоризации данных.
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
     * @return int group nomber
     */
    public function groupNom($cat) {
        return isset( $this->_category_group[$cat]) ? $this->_category_group[$cat] : NULL;
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
        0 => 'Негативные',
        1 => 'Сомнительные',
        2 => 'Нейтральные',
        3 => 'Положительные',
        4 => 'Негативные (для детей)',
        5 => 'Сомнительные (для детей)',
        6 => 'Положительные (для детей)',
    );
    
    
    public $repSupremums = array(0, 20, 40, 60, 80);
    
    public $repDescription = array(
        'Очень плохо',         // 0
        'Плохо',               // 20
        'Неудовлетворительно', // 40
        'Хорошо',              // 60
        'Превосходно',         // 80
    );
    
    const REP_DESCR_UNDEF = 'Неизвестно';
    
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
        101 => 'Вредоносные программы или вирусы',
        102 => 'Отрицательный опыт сотрудничества',
        103 => 'Фишинг',
        104 => 'Мошенничество',
        105 => 'Потенциально незаконное',
        //Questionable
        201 => 'Не этичный или вводящий в заблуждение',
        202 => 'Риски конфиденциальности',
        203 => 'Подозрительный',
        204 => 'Ненависть, дискриминация',
        205 => 'Спам',
        206 => 'Потенциально нежелательное ПО',
        207 => 'Реклама, поп-апы',
        //Neutral
        301 => 'Слежка за пользователями',
        302 => 'Альтернативная или спорная медицина',
        303 => 'Мнения, религия, политика',
        304 => 'Прочее',
        //Positive
        501 => 'Хороший сайт',
        
        //The following categories provide additional information about child safety:
        //Negative (child)
        401 => 'Сайт только для взрослых',
        //Questionable (child)
        402 => 'Случайное обнажение',
        403 => 'Отвратительный / шокирующий материал',
        //Positive (child)
        404 => 'Сайт для детей',
    );
    
    
    /**
      *    Сторонние источники данных
      *    Если веб-сайт находится в стороннем черном списке и это влияет на репутацию сайта,
      *    то API возвращает информацию о типе черного списка, в котором  сайт был найден, сайт был добавлен в этот список.
      *    Ниже приведен список типов черных списков:
      *
      *    Примечание. Если сайт находится в нескольких сторонних черных списках одного типа, то будет возвращена или дата последнего занесения в список.
      */
    
    //Blacklist type 	Description
    private $_blacklist = array(
        'malware'  => 'Сайт занесен в черный список за распространение вредоносных программ.',
        'phishing' => 'Сайт занесен в черный список за фишинг-страницы.',
        'scam'     => 'Сайт занесен в черный список за распространение машеннических материалов.',
        'spam'     => 'Сайт занесен в черный список за рассылку спама или рекламу в рассылаемом спаме.',
    );

}
