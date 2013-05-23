<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/hello', function() {
    return 'Hello!';
   //TODO
});

//Данные о контенте
$blogPosts = array(
    'hey' => array(
        'id'        => 'hey',
        'date'      => '2011-03-29',
        'author'    => 'igorw',
        'title'     => 'Using Silex',
        'body'      => 'Thats how it works',
    ),
    'yo' => array(
        'date'      => '2012-03-29',
        'author'    => 'leemour',
        'title'     => 'Пользуемся Silex',
        'body'      => 'Ура! работает',
    ),
);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// define "global" controllers
$app->get('/', function () use ($app) {
    return $app['locale'] . ' ' . $app['request']->get('locale');
});

// define controllers for a blog
$blog = $app['controllers_factory'];
$blog->get('/', function () {
    return 'Blog home page';
});
//Контроллер записей блога
$blog->get('/{id}', function (Silex\Application $app, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "Post $id does not exist.");
    }

    $post = $blogPosts[$id];

    return  "<h1>{$post['title']}</h1>".
            "<p>{$post['body']}</p>";
});

$app->mount('/blog', $blog);

//Перевод
$app->before(function () use ($app) {
    // ... инициализация Twig
 
    // Определяем локаль
    if ($locale = $app['request']->get('locale')) {
        $app['locale'] = $locale;
    } else {
        $app['locale'] = 'en';
    }
    $app->register(new Silex\Provider\TranslationServiceProvider(), array(
        'locale_fallback' => 'en',
    ));
});

$app->run();