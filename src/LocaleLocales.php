<?php
/**
 * Контент страницы
 *
 */
namespace Parcels;

class LocaleLocales {
    public $uri = '';
    public $class = '';
    
    public function __construct($name, $active) {
        //не добавляем раздел 'ru' в URI
        if ($name != 'ru')
            $this->uri = '/' . $name;
        if ($active) {
            $this->class = 'active';
        }
    }
}