WotApi
======

PHP обертка для API Web of Trust (WOT)

## Как использовать
см. demo.php или следующие примеры:

Инициализация
```php
require 'categories_en.php';
require 'wotobj.php';

$wot = new Wot('<put your api key here>');
```
При инициализации, вы можете также указать домен или список доменов:
```php
$wot = new Wot('<put your api key here>', 'github.com');
```
или
```php
$wot = new Wot('<put your api key here>', array('github.com', 'google.com'));
```
Добавить домены можно после инициализации
```php
$wot = new Wot($key, $domains);
$wot->add('www.1gs.ru');
$wot->add('msk.1gs.ru');
```
Получение списка результатов:
```php
$wotList = $wot->get();
```
Или все тоже самое одной строкой:
```php
$wotList = (new Wot($key, $domains))->add('www.1gs.ru')->add('msk.1gs.ru')->get();
```

Переменная `$wotList` является объектом класса `WotList`, но обладает всеми свойствами php-массива.
```php
echo count( $wotList ); // get list size
$wotList['msk.1gs.ru'];  // retrieve oblect of WotObj
// iterate:
foreach($wotList as $domen => $wotObj) {
    $wotObj->lang = 'ru';
    // ...
}
```
В процессе итерации объекта класса `WotList` или при доступе через оператор `[]`  (см примеры выше) извлекается объект класса `WotObj`.

Пример:
```php
$wotObj = $wotList['msk.1gs.ru'];  // instance of WotObj
$wotObj->target();                 // target domain
$wotObj->lang = 'ru';              // set url language (default English)
$wotObj->url();                    // url to WOT domin page

$wotObj->trustWorthiness();
$wotObj->trustWorthiness()->reputation();
$wotObj->trustWorthiness()->confidence();
$wotObj->trustWorthiness()->ico();
$wotObj->trustWorthiness()->description();
$wotObj->trustWorthiness()->icoIndex();

$wotObj->childSafety();
$wotObj->childSafety()->reputation();
$wotObj->childSafety()->confidence();
$wotObj->childSafety()->ico();
$wotObj->childSafety()->description();
$wotObj->childSafety()->icoIndex();

$wotObj->blacklists();           // если домен в блеклисте сторонних сервисов, то эта функция позволяет получить информацию

// Так же вы можете итерировать объект $wotObj для получения доступа к категориям
foreach($wotObj as $catKey => $cat) {
    $cat->reputation();
    $cat->group();
    $cat->description();
}

```
Для более подробной информации смотрите исходные коды.

## License BSD
Вы можете делать с моим кодом что угодно, но я ни за что ответственности не несу. Используйте на свой страх и риск.

## English description
see [README.md](https://github.com/valmat/WotApi/blob/master/README.md)

