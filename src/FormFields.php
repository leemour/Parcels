<?php
/**
 * Поля формы
 */
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