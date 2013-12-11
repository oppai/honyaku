<?php
$app = new Silex\Application();
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../views',
));
$app['debug'] = true;
$app['asset_path'] = '/honyaku/public';

$app->get("/", function () use ($app) {
  return 'hello';
});
 
$app->get('/trans/{text}', function ($text) use ($app) {
  $trans = new Translate();
  $val = $trans->getRequest($text,'en','ja');
  return $app['twig']->render('index.html.twig',array('text'=>$val));
});

return $app;
