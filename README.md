WotApi
======

Web of Trust (WOT) PHP API wrapper

## How to use
see demo.php or the following examples:

initialization
```php
require 'categories_en.php';
require 'wotobj.php';

$wot = new Wot('<put your api key here>');
```
At initialization, you can also specify a domain or a list of domains:
```php
$wot = new Wot('<put your api key here>', 'github.com');
```
or
```php
$wot = new Wot('<put your api key here>', array('github.com', 'google.com'));
```
You can add domains after initialization

```php
$wot = new Wot($key, $domains);
$wot->add('www.1gs.ru');
$wot->add('msk.1gs.ru');
```
Get a list of results:
```php
$wotList = $wot->get();
```
The variable `$wotList` is a object of class `WotList`, but has all the features of a php array.
```php
echo count( $wotList ); // get list size
$wotList['msk.1gs.ru'];  // retrieve oblect of WotObj
// iterate:
foreach($wotList as $domen => $wotObj) {
    $wotObj->lang = 'ru';
    // ...
}
```
During iteration object of class `WotList` or when accessed through the operator `[]` (see the examples above) is retrieved objects of class  `WotObj`.

Example:
```php
$wotObj = $wotList['msk.1gs.ru'];  // instance of WotObj
$wotObj->target();                 // target domain
$wotObj->lang = 'ru';              // set url language (default English)
$wotObj->url();                    // url to WOT domin page

$wotObj->trustWorthiness();
$wotObj->trustWorthiness()->confidence();
$wotObj->trustWorthiness()->ico();
$wotObj->trustWorthiness()->description();
$wotObj->trustWorthiness()->icoIndex();

$wotObj->childSafety();
$wotObj->childSafety()->confidence();
$wotObj->childSafety()->ico();
$wotObj->childSafety()->description();
$wotObj->childSafety()->icoIndex();

$wotObj->blacklists();           // if the domain in blacklist third-party services, this function allows you to receive information

// Also you can iterate object $wotObj to access categories
foreach($wotObj as $catKey => $cat) {
    $cat->reputation();
    $cat->group();
    $cat->description();
}

```
For more information see the source code.

## License BSD
You can do anything with my code, but I'm for anything not responsible. Use at your own risk.

## Russian description
see [READMEru.md](https://github.com/valmat/WotApi/blob/master/READMEru.md)
