<?php
/**
 * Все данные о структуре, заголовках, названиях меню и т.д.
 *
 */
namespace Parcels;

/**
 * SEO
 * Управление <title>, keywords, description
 */
class PageMeta {
    private $title = array();
    private $description = '';
    private $keywords = '';
    
    public function __construct() {
        $this->title = array (
            '404'               => 'Ошибка 404',
            'china'             => 'Доставка грузов из Китая',
            'contacts'          => 'Запросите нас',
            'container'         => 'Сборные грузы и контейнерные перевозки',
            'customs'           => 'Таможенное оформление груза',
            'delivery'          => 'Доставка посылок',
            'distribution'      => 'Рассылка посылок по адресам',
            'export'            => 'Экспорт в любую точку мира',
            'geography'         => 'География услуг',
            'import'            => 'Доставка по Российской Федерации',
            'links'             => 'Полезные ссылки',
            'main'              => 'Эффективная доставка бизнес отправлений',
            'payment'           => 'Организация оплаты',
            'request'           => 'Обработка запроса',
            'transport-types'   => 'Виды перевозок',
        );
        //Управление description, keywords
        $this->description = array(
            'Доставка коммерческих посылок любого размера в Россию и в любую точку мира. Таможенное оформление грузов. Авиа-, жд-, авто-, морские грузоперевозки, организация оплаты.'
        );
        $this->keywords = array(
            'международная доставка грузов, служба доставки, доставка посылок, отправление посылок, доставка Китай, доставка Германия, таможенное оформление посылок, коммерческий груз'
        );
    }
    
    public function getTitle($slug) {
        return $this->title[$slug];
    }
    public function getDescription($slug) {
        return $this->description[0];
    }
    public function getKeywords($slug) {
        return $this->keywords[0];
    }
}

/**
 * СТРУКТУРА И ССЫЛКИ
 */
class PageStructure {
    private $parentPages = array();
    private $mainMenu = array();
    private $sideMenu = array();
    
    function __construct() {
        //Родительские страницы
        $this->parentPages = array (
            'china'             => 'geography',
        );
        //Меню основное
        $this->mainMenu = array (
            array(
                'slug'  => '/',
                'text'  => 'Главная',
                'title' => 'На главную',
            ),
            array(
                'slug'  => 'delivery',
                'text'  => 'Доставка посылок',
                'title' => 'Все о доставке посылок',
            ),
            array(
                'slug'  => 'payment',
                'text'  => 'Организация оплаты',
                'title' => 'Прием оплаты и оплата поставщикам',
            ),
            array(
                'slug'  => 'transport-types',
                'text'  => 'Виды перевозок',
                'title' => 'Авиа, железнодорожные, авто и морские грузоперевозки',
            ),
            array(
                'slug'  => 'links',
                'text'  => 'Полезные ссылки',
                'title' => 'Ссылки на сайты о таможне и грузоперевозках',
            ),
            array(
                'slug'  => 'contacts',
                'text'  => 'Запросите нас',
                'title' => 'Контакты и форма заявки',
            ),
        );
        
        //Меню боковое
        $this->sideMenu = array (
            array(
                'slug'  => 'delivery#commercial',
                'text'  => 'Доставка посылок',
                'title' => 'На главную',
            ),
            array(
                'slug'  => 'customs',
                'text'  => 'Таможенное оформление',
                'title' => 'Все о доставке посылок',
            ),
            array(
                'slug'  => 'import',
                'text'  => 'Доставка по России',
                'title' => 'Прием оплаты и оплата поставщикам',
            ),
            array(
                'slug'  => 'export',
                'text'  => 'Экспорт из России',
                'title' => 'Авиа, железнодорожные, авто и морские грузоперевозки',
            ),
            array(
                'slug'  => 'payment',
                'text'  => 'Оплата за товар',
                'title' => 'Ссылки на сайты о таможне и грузоперевозках',
            ),
            array(
                'slug'  => 'distribution',
                'text'  => 'Рассылка посылок',
                'title' => 'Контакты и форма заявки',
            ),
            array(
                'slug'  => 'payment#receiver',
                'text'  => 'Доставка за счет получателя',
                'title' => 'Авиа, железнодорожные, авто и морские грузоперевозки',
                'parent'=> '/payment',
            ),
            array(
                'slug'  => 'container',
                'text'  => 'Контейнерные перевозки',
                'title' => 'Ссылки на сайты о таможне и грузоперевозках',
            ),
            array(
                'slug'  => 'container#consolidation',
                'text'  => 'Сборные грузы',
                'title' => 'Контакты и форма заявки',
                'parent'=> '/container',
            ),
            array(
                'slug'  => 'geography',
                'text'  => 'География услуг',
                'title' => 'Контакты и форма заявки',
            ),
        );
    }
    
    public function getParent($slug) {
        return $this->parentPages[$slug] ?: false;
    }
    
    public function mainMenu($slug) {
        foreach ($this->mainMenu as $item) {
            if ($item['slug'] === $slug)
                return $item['slug'];
        }
        return false;
    }
    
    public function sideMenu($page) {
        foreach ($this->sideMenu as $item) {
            if ($item['slug'] === $slug)
                return $item['slug'];
        }
        return false;
    }
    
    public function pageExists($page) {
        function in_menu($page, $menu) {
            foreach ($menu as $item) {
                if ($item['slug'] === $page)
                    return true;
            }
            return false;
        }
        
        if (in_menu($page, $this->mainMenu) || in_menu($page, $this->sideMenu)) {
            return true;
        }
        return false;
    }
}

//Поля формы
class FormFields {
    private $form_fields;
    
    function __construct() {
        $this->form_fields = array(
            'r-description' => array(
                'name'      => 'r-description',
                'text'      => 'Описание груза',
                'type'      => 'textarea',
                'class'     => 'textarea-2',
                'maxlength' => '255',
                'group'     => 'freight',
            ),
            'r-price' => array(
                'name'      => 'r-price',
                'text'      => 'Стоимость груза (укажите валюту)',
                'text2'     => 'Стоимость груза в валюте',
                'type'      => 'text',
                'maxlength' => '20',
                'group'     => 'freight',
            ),
            'r-quantity' => array(
                'name'      => 'r-quantity',
                'text'      => 'Количество и габариты коробок',
                'type'      => 'textarea',
                'class'     => 'textarea-4',
                'maxlength' => '255',
                'group'     => 'freight',
            ),
            'r-weight' => array(
                'name'      => 'r-weight',
                'text'      => 'Общий вес (кг)',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '20',
                'group'     => 'freight',
            ),
            'r-size' => array(
                'name'      => 'r-size',
                'text'      => 'Общий объем (м&#179;)',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '20',
                'group'     => 'freight',
            ),
            'r-origin' => array(
                'name'      => 'r-origin',
                'text'      => 'Страна и город отправления',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '100',
                'group'     => 'conditions',
            ),
            'r-destination' => array(
                'name'      => 'r-destination',
                'text'      => 'Страна и город назначения',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '100',
                'group'     => 'conditions',
            ),
            'r-services' => array(
                'name'      => 'r-services',
                'text'      => 'Требующиеся услуги',
                'type'      => 'textarea',
                'class'     => 'textarea-1',
                'maxlength' => '255',
                'group'     => 'conditions',
            ),
            'r-name' => array(
                'name'      => 'r-name',
                'text'      => 'Ваше имя',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '30',
                'group'     => 'contact-info',
            ),
            'r-mail' => array(
                'name'      => 'r-mail',
                'text'      => 'Ваш e-mail для связи',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '30',
                'group'     => 'contact-info',
            ),
            'r-tel' => array(
                'name'      => 'r-tel',
                'text'      => 'Ваш телефон для связи',
                'type'      => 'text',
                'class'     => '',
                'maxlength' => '20',
                'group'     => 'contact-info',
            ),
        );
    }
    
    function field($field) {
        return $this->form_fields[$field];
    }
}

class PageData {
    private $slug = '';
    private $title = '';
    private $description = '';
    private $keywords = '';
    
    public function __construct($page, $pageMeta, $pageStructure) {
        $this->slug         = $page;
        $this->title        = $pageMeta->getTitle($page);
        $this->description  = $pageMeta->getDescription($page);
        $this->keywords     = $pageMeta->getKeywords($page);
    }
}