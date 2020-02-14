<?php
$this->get('', function($arg){
    $tpl = $this->core->loadModule('template');

    $tpl->render('home');
});

$this->get('/criar-usuario', function($arg){
    $tpl = $this->core->loadModule('template');

    $tpl->render('criar-usuario');
});

$this->post('/criar-usuario', function($arg){
    $login = $this->core->loadModule('login');
    return $login->criarUsuario();
});

$this->loadRouteFile('noticias');
