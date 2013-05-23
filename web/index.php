<?php
//ini_set('display_errors', 0);

# КОНФИГУРАЦИЯ
require __DIR__ . '/../src/config.php';

use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Validator\Constraints as Assert;

//Стандартный Silex init
require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;
$app['locale'] = 'ru';

/**
 * Регистрация сервисов
 */
//Перевод
$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => 'ru',
));

$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    $translator->addResource('yaml', CONTENT_PATH . '/locales/en.yml', 'en');
    return $translator;
}));

//Логгер
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile'       => __DIR__.'/../log/app.log',
    'monolog.name'          => 'app',
    'monolog.level'         => 300 // = Logger::WARNING
));

//Шаблонизатор
$app->register(new TwigServiceProvider(), array(
    'twig.options'  => array(
                        'cache' => false,
                        'auto_reload' => true,
                        'strict_variables' => true
                    ),
    'twig.path'     => array(__DIR__ . '/../views', CONTENT_PATH)
));

//Меню
$app->register(new \Knp\Menu\Silex\KnpMenuServiceProvider($app));

//Формы
$app->register(new FormServiceProvider());

//Валидация
$app->register(new Silex\Provider\ValidatorServiceProvider());

//Инициализация модели (просто массивы)
require SRCPATH . '/Page.php';
//Инициализация модели (объекты из массивов)
$app['meta']    = $app->share( function($app) { return new \Parcels\PageMeta($app['locale']); });
$app['pages']   = $app->share( function() { return new \Parcels\PageStructure(); });
$app['fields']  = $app->share( function() { return new \Parcels\FormFields(); });
$app['validationer']  = $app->share( function() { return new \Parcels\Validation(); });

//Создаем Главное Меню
$app['main_menu'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('Main menu');
    $wrapper = $app['main_menu.wrapper'] = new Parcels\PageMenu($menu, $app['pages'], $app['locale']);
    $wrapper->setChildrenAttributes(array('id' => 'navigation-main', 'class' => 'navigation'));

    $wrapper->addItem('/');
    $wrapper->addItem('delivery');
    $wrapper->addItem('payment');
    $wrapper->addItem('transport-types');
    $wrapper->addItem('links');
    $wrapper->addItem('contacts');

    return $menu;
};

//Создаем Боковое Меню
$app['side_menu'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('Side menu');
    $wrapper = $app['side_menu.wrapper'] = new Parcels\PageMenu($menu, $app['pages'], $app['locale']);
    $wrapper->setChildrenAttributes(array('class' => 'services-list'));

    $wrapper->addItem('commercial-parcels');
    $wrapper->addItem('customs');
    $wrapper->addItem('import');
    $wrapper->addItem('export');
    $wrapper->addItem('payment',    array('label' => 'Оплата за товар'));
    $wrapper->addChild($wrapper->getItem('payment'), 'payment#receiver');
    $wrapper->addItem('distribution');
    $wrapper->addItem('container');
    $wrapper->addChild($wrapper->getItem('container'), 'container#consolidation');
    $wrapper->addItem('geography');
    $wrapper->addChild($wrapper->getItem('geography'), 'china');

    return $menu;
};

//Создаем Breadcrums
$app['breadcrumbs'] = function($app) {
    $menu = $app['knp_menu.factory']->createItem('Breadcrumbs');
    $wrapper = new Parcels\PageMenu($menu, $app['pages'], $app['locale']);
    $wrapper->setChildrenAttributes(array('id' => 'breadcrumbs'));

    $wrapper->addItem('delivery',   array('uri' => 'delivery#commercial'));
    $wrapper->addItem('customs');
    $wrapper->addItem('import');
    $wrapper->addItem('export');
    $wrapper->addItem('payment',    array('label' => 'Оплата за товар'));
    $wrapper->addChild($wrapper->getItem('payment'), 'payment#receiver');
    $wrapper->addItem('distribution');
    $wrapper->addItem('container');
    $wrapper->addChild($wrapper->getItem('container'), 'container#consolidation');
    $wrapper->addItem('geography');
    $wrapper->addChild($wrapper->getItem('geography'), 'china');
    $wrapper->addItem('transport-types');
    $wrapper->addItem('commercial-parcels');
    $wrapper->addItem('links');
    $wrapper->addItem('contacts');
    $wrapper->addItem('request');

    return $menu;
};

//Регистрируем меню (для вывода в шаблон)
$app['knp_menu.menus'] = array('main' => 'main_menu', 'side' => 'side_menu', 'breadcrumbs' => 'breadcrumbs');
$app['knp_menu.default_renderer'] = 'twig';
//$app['knp_menu.template'] = 'knp_menu.html.twig';

//Создаем формы
//Поля - НЕ ИСПОЛЬЗУЕТСЯ
$app['form_fields'] = array(
    'description'   => 'Описание груза',
    'value'         => 'Стоимость груза в валюте',
    'boxes'         => 'Количество и габариты коробок',
    'weight'        => 'Общий вес (кг)',
    'volume'        => 'Общий объем (м³)',
    'origin'        => 'Страна и город отправления',
    'destination'   => 'Страна и город назначения',
    'services'      => 'Требующиеся услуги',
    'name'          => 'Ваше имя',
    'email'         => 'Ваш e-mail',
    'tel'           => 'Ваш телефон для связи'
);

//Форма основная
$app['main_form'] = $app['form.factory']->createBuilder('form')
    ->add('description', 'text', array(
        'required'      => false,
        'label'         => 'Описание груза',
    ))
    ->add('value', 'text', array(
        'required'      => false,
        'label'         => 'Стоимость груза в валюте',
    ))
    ->add('boxes', 'text', array(
        'required'      => false,
        'label'         => 'Количество и габариты коробок',
    ))
    ->add('weight', 'text', array(
        'required'      => false,
        'label'         => 'Общий вес (кг)',
    ))
    ->add('volume', 'text', array(
        'required'      => false,
        'label'         => 'Общий объем (м³)',
    ))
    ->add('origin', 'text', array(
        'required'      => false,
        'label'         => 'Страна и город отправления',
    ))
    ->add('destination', 'text', array(
        'required'      => false,
        'label'         => 'Страна и город назначения',
    ))
    ->add('services', 'text', array(
        'required'      => false,
        'label'         => 'Требующиеся услуги',
    ))
    ->add('name', 'text', array(
        'required'      => false,
        'label'         => 'Ваше имя'
    ))
    ->add('email', 'text', array(
        'label'         => 'Ваш e-mail'
    ))
    ->add('tel', 'text', array(
        'required'      => false,
        'label'         => 'Ваш телефон для связи'
    ))
    ->getForm();

# Контроллеры
require SRCPATH . '/controllers.php';
