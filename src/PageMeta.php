<?php
/**
 * SEO
 * Управление <title>, keywords, description
 */
namespace Parcels;

class PageMeta {
    private $title = array();
    private $description = '';
    private $keywords = '';
    
    public function __construct($locale) {
        $this->title = array (
            '/'                 => 'Эффективная доставка бизнес отправлений',
            '404'               => 'Ошибка 404',
            '500'               => 'Ошибка 500',
            'china'             => 'Доставка грузов из Китая',
            'contacts'          => 'Запросите нас',
            'container'         => 'Сборные грузы и контейнерные перевозки',
            'customs'           => 'Таможенное оформление груза',
            'delivery'          => 'Доставка посылок',
            'commercial-parcels'=> 'Доставка коммерческих посылок',
            'distribution'      => 'Рассылка посылок по адресам',
            'export'            => 'Экспорт в любую точку мира',
            'geography'         => 'География услуг',
            'import'            => 'Доставка по Российской Федерации',
            'links'             => 'Полезные ссылки',
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