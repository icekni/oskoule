<?php

// Premiere chose, require de l'autoload qui va require automatiquement nos librairies tierces
require __DIR__ . '/../vendor/autoload.php';

// On initialise la session
session_start();

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

// Route pour afficher la liste des profs
$router->map(
    'GET',
    '/teacher/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-list'
);

// Route pour afficher le formulaire d'ajout d'un prof
$router->map(
    'GET',
    '/teacher/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-add'
);

// Route qui recevra l'envoi du formulaire d'ajout d'un prof
$router->map(
    'POST',
    '/teacher/add',
    [
        'method' => 'addPost',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-addpost'
);

// Route pour afficher le formulaire d'edition d'un prof
$router->map(
    'GET',
    '/teacher/[i:id]',
    [
        'method' => 'edit',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-edit'
);

// Route qui recevra l'envoi du formulaire d'edition d'un prof
$router->map(
    'POST',
    '/teacher/[i:id]',
    [
        'method' => 'editPost',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-editpost'
);

// Route pour afficher le formulaire de suppression d'un prof
$router->map(
    'GET',
    '/teacher/[i:id]/delete/[a:token]?',
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\TeacherController'
    ],
    'teacher-delete'
);

// --------------------------------
// Routes Student

// Route pour afficher la liste des etudiants
$router->map(
    'GET',
    '/student/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-list'
);

// Route pour afficher le formulaire d'ajout d'un etudiant
$router->map(
    'GET',
    '/student/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-add'
);

// Route qui recevra l'envoi du formulaire d'ajout d'un etudiant
$router->map(
    'POST',
    '/student/add',
    [
        'method' => 'addPost',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-addpost'
);

// Route pour afficher le formulaire d'edition d'un etudiant
$router->map(
    'GET',
    '/student/[i:id]',
    [
        'method' => 'edit',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-edit'
);

// Route qui recevra l'envoi du formulaire d'edition d'un etudiant
$router->map(
    'POST',
    '/student/[i:id]',
    [
        'method' => 'editPost',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-editpost'
);

// Route pour afficher le formulaire de suppression d'un etudiant
$router->map(
    'GET',
    '/student/[i:id]/delete/[a:token]?',
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\StudentController'
    ],
    'student-delete'
);

// --------------------------------
// Route de connexion/deconnexion

// Route pour se connecter
$router->map(
    'GET',
    '/login',
    [
        'method' => 'login',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-login'
);

// Route pour receptionner le formulaire de connexion
$router->map(
    'POST',
    '/login',
    [
        'method' => 'loginPost',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-loginpost'
);

// Route pour se connecter
$router->map(
    'GET',
    '/logout',
    [
        'method' => 'logout',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-logout'
);

// Route pour afficher la liste des users
$router->map(
    'GET',
    '/user/list',
    [
        'method' => 'list',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-list'
);

// Route pour afficher le formulaire d'ajout d'un user
$router->map(
    'GET',
    '/user/add',
    [
        'method' => 'add',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-add'
);

// Route qui recevra l'envoi du formulaire d'ajout d'un user
$router->map(
    'POST',
    '/user/add',
    [
        'method' => 'addPost',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-addpost'
);

// Route pour afficher le formulaire d'edition d'un user
$router->map(
    'GET',
    '/user/[i:id]',
    [
        'method' => 'edit',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-edit'
);

// Route qui recevra l'envoi du formulaire d'edition d'un user
$router->map(
    'POST',
    '/user/[i:id]',
    [
        'method' => 'editPost',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-editpost'
);

// Route pour afficher le formulaire de suppression d'un user
$router->map(
    'GET',
    '/user/[i:id]/delete/[a:token]?',
    [
        'method' => 'delete',
        'controller' => '\App\Controllers\UserController'
    ],
    'user-delete'
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