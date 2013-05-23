<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Контроллеры
 */

//Контроллер английской версии
$en = $app['controllers_factory'];
//Контроллер Формы запросов
$en->match('/request', function (Request $request) use ($app) {
    $app['locale'] = 'en';
    //Существует ли страница?
    if (!$app['pages']->getUri('request'))
        throw new NotFoundHttpException($app['locale']);
    
    $page = new \Parcels\Page($app, 'request');
    //$locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    //Если пришли данные формы
    $form = $app['main_form'];
    $error = false;
    if ('POST' == $request->getMethod()) {
        $form->bind($request);
        $data = $form->getData();
        
        if (!$form->isValid())
            $error = true;
    }

    return $app['twig']->render('request.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $page->locale,
        'form'          => $form->createView(),
        'error'         => $error,
    ));
});


$en->get('/{slug}', function (Silex\Application $app, $slug) {
    $app['locale'] = 'en';
    //$app['translator']['locale'] = 'en';
    //Существует ли страница?
    if (!$app['pages']->getUri($slug))
        throw new NotFoundHttpException($app['locale']);
    
    $page = new \Parcels\Page($app, $slug);
    $locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    return $app['twig']->render('layout.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $locale,
        'form'          => $app['main_form']->createView(),
    ));
})
->value('slug', '/');
    
//Контроллер второго уровня структуры сайта
$en->get("/{parent}/{slug}", function (Silex\Application $app, $parent, $slug) {
    $app['locale'] = 'en';
    //Существует ли страница?
    if (!$app['pages']->getUri($slug))
        throw new NotFoundHttpException($app['locale']);
    
    $page = new \Parcels\Page($app, $slug);
    $locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    return $app['twig']->render('layout.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $locale,
        'form'          => $app['main_form']->createView(),
    ));
});

$app->mount('/en', $en);

// Контроллер Русской версии


//Контроллер Формы запросов
$app->match('/request', function (Request $request) use ($app) {
    $app['locale'] = 'ru';
    //Существует ли страница?
    if (!$app['pages']->getUri('request'))
        throw new NotFoundHttpException($app['locale']);
    
    $page = new \Parcels\Page($app, 'request');
    //$locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    //Если пришли данные формы
    $form = $app['main_form'];
    $error = false;
    if ('POST' == $request->getMethod()) {
        $form->bind($request);
        $data = $form->getData();
        
        if (!$form->isValid())
            $error = true;
    }

    return $app['twig']->render('request.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $page->locale,
        'form'          => $form->createView(),
        'error'         => $error,
    ));
});

$app->get('/{slug}', function (Silex\Application $app, $slug) {
    $app['locale'] = 'ru';
    //Существует ли страница?
    if (!$app['pages']->getUri($slug))
        throw new NotFoundHttpException($app['locale']);
    
    $page = new \Parcels\Page($app, $slug);
    $locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    return $app['twig']->render('layout.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $locale,
        'form'          => $app['main_form']->createView(),
    ));
})
->value('slug', '/');

//Контроллер второго уровня структуры Русской версии сайта
$app->get("/{parent}/{slug}", function (Silex\Application $app, $parent, $slug) {
    $app['locale'] = 'ru';
    //Существует ли страница?
    if (!$app['pages']->getUri($slug))
        throw new NotFoundHttpException($app['locale']);
    
    $page = new \Parcels\Page($app, $slug);
    $locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    return $app['twig']->render('layout.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $locale,
        'form'          => $app['main_form']->createView(),
    ));
});

//Обработка ошибок
$app->error(function (NotFoundHttpException $e, $code) use ($app) {
    //Устанавливаем локаль в значение, переданное контроллером
    $app['locale'] = $e->getMessage();
    if ($app['debug']) {
        //return;
    }
    $code = '404' == $code ? '404': '500';
    $page = new \Parcels\Page($app, $code);
    $locale = new \Parcels\Locale($app, array('ru', 'en'));
    
    return new Response($app['twig']->render('layout.html.twig', array(
        'page'          => $page,
        'email'         => EMAIL,
        'image_path'    => IMAGE_PATH,
        'locale'        => $locale,
    )), $code);
});

$app->run();