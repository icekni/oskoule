<?php

// Premiere chose, require de l'autoload qui va require automatiquement nos librairies tierces
require __DIR__ . '/../vendor/autoload.php';

// TODO session_start()

// ========================================
// Routes
// ========================================

$router = new AltoRouter();

// On definit le base path
if (array_key_exists('BASE_URI', $_SERVER)) {
    $router->setBasePath($_SERVER['BASE_URI']);
}
else {
    $_SERVER['BASE_URI'] = '/';
}

// Route home
$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => '\App\Controllers\MainController'
    ],
    'main-home'
);

// --------------------------------
// Routes Teacher

$router->map(
    'GET',
    '/teacher/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-list'
);

// --------------------------------
// Routes Student

$router->map(
    'GET',
    '/student/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-list'
);


// ========================================
// Dispatcher
// ========================================

// Altorouter se charge de trouver la route en cours
$match = $router->match();

// On transmet au dispatcher de Ben avec comme argument le controller et la methode (static) pour afficher la page 404
$dispatcher = new Dispatcher($match, '\App\Controllers\ErrorController::err404');
// Et on dispatch
$dispatcher->dispatch();