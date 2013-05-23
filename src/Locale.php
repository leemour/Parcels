<?php
/**
 * Контент страницы
 *
 */
namespace Parcels;

require 'LocaleLocales.php';

class Locale {
    //Общий путь для ссылок в контенте
    public $uri = '';
    
    public function __construct($app, $locales = array()) {
        if (!is_array($locales))
            throw new RuntimeException('Неправильно заданы локали');
        foreach ($locales as $locale) {
            $active = '';
            if ($app['locale'] == $locale)
                $active = 'active';
            $this->register($locale, $active);
        }
        $this->uri = $app['locale'] == 'ru'? '': '/' . $app['locale'];
    }
    
    public function register($name, $active = false) {
        $this->$name = new LocaleLocales($name, $active);
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}