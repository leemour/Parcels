<?php
/**
 * Иерархия страниц с данными
 */
namespace Parcels;

class PageStructure {
    private $pages = array();
    private $parentPages = array();
    private $innerPages = array();
    
    function __construct() {
        //Родительские страницы
        $this->parentPages = array (
            'china'                     => 'geography',
        );
        //Ссылки на элемент страницы
        $this->innerPages = array (
            'container#consolidation'   => 'container',
            'payment#receiver'          => 'payment',
        );
        //Все страницы
        $this->pages = array (
            array(
                'slug'  => '/',
                'label' => 'Главная',
                'title' => 'На главную',
            ),
            array(
                'slug'  => '404',
                'label' => 'Ошибка 404',
                'title' => 'Страница не найдена',
            ),
            array(
                'slug'  => 'delivery',
                'label' => 'Доставка посылок',
                'title' => 'Все о доставке посылок',
            ),
            array(
                'slug'  => 'commercial-parcels',
                'label' => 'Коммерческие посылки',
                'title' => 'Коммерческие посылки',
            ),
            array(
                'slug'  => 'payment',
                'label' => 'Организация оплаты',
                'title' => 'Прием оплаты и оплата поставщикам',
            ),
            array(
                'slug'  => 'transport-types',
                'label' => 'Виды перевозок',
                'title' => 'Авиа, железнодорожные, авто и морские грузоперевозки',
            ),
            array(
                'slug'  => 'links',
                'label' => 'Полезные ссылки',
                'title' => 'Ссылки на сайты о таможне и грузоперевозках',
            ),
            array(
                'slug'   => 'contacts',
                'label' => 'Запросите нас',
                'title' => 'Контакты и форма заявки',
            ),
            array(
                'slug'  => 'delivery#commercial',
                'label' => 'Доставка посылок',
                'title' => 'На главную',
            ),
            array(
                'slug'  => 'customs',
                'label' => 'Таможенное оформление',
                'title' => 'Все о доставке посылок',
            ),
            array(
                'slug'  => 'import',
                'label' => 'Доставка по России',
                'title' => 'Прием оплаты и оплата поставщикам',
            ),
            array(
                'slug'  => 'export',
                'label' => 'Экспорт из России',
                'title' => 'Авиа, железнодорожные, авто и морские грузоперевозки',
            ),
            //array(
            //    'slug'   => 'payment',
            //    'label' => 'Оплата за товар',
            //    'title' => 'Ссылки на сайты о таможне и грузоперевозках',
            //),
            array(
                'slug'  => 'distribution',
                'label' => 'Рассылка посылок',
                'title' => 'Контакты и форма заявки',
            ),
            array(
                'slug'  => 'payment#receiver',
                'label' => 'Доставка за счет получателя',
                'title' => 'Авиа, железнодорожные, авто и морские грузоперевозки',
                'parent'=> 'payment',
            ),
            array(
                'slug'  => 'container',
                'label' => 'Контейнерные перевозки',
                'title' => 'Ссылки на сайты о таможне и грузоперевозках',
            ),
            array(
                'slug'  => 'container#consolidation',
                'label' => 'Сборные грузы',
            ),
            array(
                'slug'  => 'geography',
                'label' => 'География услуг',
                'title' => 'Контакты и форма заявки',
            ),
            array(
                'slug'  => 'china',
                'label' => 'Доставка из Китая',
                'title' => 'Доставка грузов из Китая',
            ),
            array(
                'slug'  => 'request',
                'label' => 'Обработка запроса',
                'title' => 'Обработка запроса',
            ),
        );
    }
    
    public function getPage($slug) {
        foreach ($this->pages as $page) {
            if ($slug === $page['slug']) {
                return $page;
            }
        }
    }
    
    public function getParent($slug) {
        return isset($this->parentPages[$slug]) ? $this->parentPages[$slug]: false;
    }
    
    public function getChildren($slug) {
        $children = array();
        foreach ($this->parentPages as $child => $parent)
            $children[] = $child;
        
        return $children?: false;
    }

    public function pageExists($slug) {
        foreach ($this->pages as $page) {
            if ($slug === $page['slug']) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getUri($slug, $children = '') {
        $slug = (string)$slug;
        $children? $uri = $children: $uri = '';
        if ($this->pageExists($slug)) {
            if ($slug != '/') 
                $uri = '/' . $slug . $uri;
            else
                $uri = $slug;
            
            if ($parent = $this->getParent($slug))
                $uri = $this->getUri($parent, $uri);  
            return $uri;
        } else {
            return false;
        }
    }
    
    public function cleanSlug($slug) {
        $slug = substr($slug, 0, strpos($slug, '#'));
        return $slug;
    }
}