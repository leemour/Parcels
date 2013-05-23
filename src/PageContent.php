<?php
/**
 * Контент страницы
 *
 */
namespace Parcels;

class PageContent {
    public $main = '';
    
    public function __construct($uri, $locale) {
        $uri == '/'? $uri = '/index': $uri;
        $uri = str_replace('/', '_', substr($uri, 1));
        $file = $uri . '.html.twig';
        if (file_exists(CONTENT_PATH . '/' . $locale . '/' . $file)) {
            $this->main = $locale . '/' . $file;
        } else {
            throw new \Exception('Контент не доступен для страницы ' . $uri);
        }
    }
}