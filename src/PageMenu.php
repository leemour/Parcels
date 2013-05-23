<?php
/**
 * Страницы меню
 *
 */
namespace Parcels;

class PageMenu {
    private $menu;
    private $pages;
    private $locale;
    
    public function __construct(\Knp\Menu\MenuItem $menu, $pages, $locale) {
        $this->menu  = $menu;
        $this->pages = $pages;
        $this->locale = $locale;
    }
    
    public function addItem($slug, $options = array(), $menuItem = false) {
        $item = $this->pages->getPage($slug);
        $defaults['name']   = $slug;
        $defaults['label']  = $item['label'];
        
        if ($this->locale == 'ru')
            $locale = '';
        else
            $locale = '/' . $this->locale;
        $defaults['uri']    = $locale . $this->pages->getUri($slug);
        $options = array_merge($defaults, $options);
        
        if (!$menuItem)
            $menuItem = $this->menu;
        $menuItem->addChild($slug, $options);
    }
    
    public function getItem($name) {
        return $this->menu[$name];
    }
    
    public function addChild($menuItem, $slug, $options = array()) {
        $this->addItem($slug, $options, $menuItem);
    }
    
    public function setChildrenAttributes(array $childrenAttributes) {
        $this->menu->setChildrenAttributes($childrenAttributes);
        return $this;
    }
    
    public function inMenu($uri) {
        foreach ($menu as $item) {
            if ($item['uri'] === $uri)
                return true;
        }
        return false;
    }
}