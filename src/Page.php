<?php
/**
 * Данные загружаемой страницы
 *
 */
namespace Parcels;

require 'PageStructure.php';
require 'PageMeta.php';
require 'PageMenu.php';
require 'PageContent.php';
require 'Locale.php';
require 'CurrentPageVoter.php';

class Page {
    public $slug = '';
    public $uri = '';
    public $title = '';
    public $description = '';
    public $keywords = '';
    public $content = NULL;
    public $parent = false;
    public $locale = NULL;

    public function __construct($app, $slug) {
        $meta = $app['meta'];
        $uri  = $app['pages']->getUri($slug);
        $locale = $app['locale'];
        $this->slug         = $slug;
        $this->uri          = $uri;
        $this->title        = $meta->getTitle($slug);
        $this->description  = $meta->getDescription($slug);
        $this->keywords     = $meta->getKeywords($slug);
        $this->content      = new PageContent($uri, $locale);
        $this->parent       = $app['pages']->getParent($slug);
        $this->locale       = new \Parcels\Locale($app, array('ru', 'en'));

        //Menu Voter для определения текущего раздела меню
        $app['knp_menu.matcher']->addVoter(new \Parcels\CurrentPageVoter($slug));

        //foreach($app['knp_menu.menus'] as $menu) {
        //    if ($app[$menu . '.wrapper']->inMenu($uri))
        //        $this->menu = $menu;
        //}
    }
}