<?php

namespace App\Controllers;

abstract class CoreController
{
    public function __construct()
    {
        // Pour gerer les permissions, j'ai besoin de connaitre le nom de la route en cours
        // Je dois donc aller la chercher dans $match
        global $match;
        $routeName = $match['name'];

        // Tableau ACL qui va definir les acces aux pages
        $acl = [
            'main-home' => ['admin', 'user'],
            'teacher-list' => ['admin', 'user'],
            'teacher-add' => ['admin'],
            'teacher-addpost' => ['admin'],
            'teacher-edit' => ['admin'],
            'teacher-editpost' => ['admin'],
            'teacher-delete' => ['admin'],
            'student-list' => ['admin', 'user'],
            'student-add' => ['admin', 'user'],
            'student-addpost' => ['admin', 'user'],
            'student-edit' => ['admin', 'user'],
            'student-editpost' => ['admin', 'user'],
            'student-delete' => ['admin', 'user'],
            'user-list' => ['admin', 'user'],
            'user-add' => ['admin', 'user'],
            'user-addpost' => ['admin', 'user'],
        ];

        // On doit verifier si le nom de la route en cours est bien dans le tableau
        if(array_key_exists($routeName, $acl)){
            // Si oui, on recupere les roles autorisés pour cette page
            $authorizedRoles = $acl[$routeName];
            // Et on utilise la methode checkAuthorization pour verifier si l'utilisateur est autorisé a acceder a la page
            $this->checkAuthorization($authorizedRoles);
        }
    }

    /**
     * Methode show
     * Elle se charge de faire les require des template necessaire pour afficher la page indiquée dans $viewName
     *
     * @param string $viewName est le nom de la vue a afficher, sous la forme 'dossier/fichierTPL
     * @param array $viewVars contient les données a transmettre a la page
     * @return void
     */
    protected function show(string $viewName, $viewVars = []): void
    {
        global $router;

        // On stocke le nom de la vue courante dans $viewVars
        $viewVars['currentPage'] = $viewName;

        // Pour avoir un acces plus pratique aux viewVars, on utilise extract qui creera une variable du nom de l'index utilisé dans $viewVars
        extract($viewVars);

        require __DIR__ . '/../Views/layout/header.tpl.php';
        require __DIR__ . '/../Views/' . $viewName . '.tpl.php';
        require __DIR__ . '/../Views/layout/footer.tpl.php';
    }


    /**
     * Methode checkAuthorization
     * Elle verifie si l'user est bien autorisé a acceder a la page
     *
     * @param array $roles
     * @return bool (true = autorisé)
     */
    protected function checkAuthorization($roles = []) : bool
    {
        global $router;

        // si le user est connecté
        if (isset($_SESSION['userId'])) {
            // On recupere l'objet $user dans la sessions
            $currentUser = $_SESSION['userObject'];

            // On récupère son role
            $currentUserRole = $currentUser->getRole();

            // On verifie dans le tableau ACL si le role de l'user est bien autorisé a acceder a la page
            if (in_array($currentUserRole, $roles)) {
                return true;
            } else {
                // si le user connecté n'a pas la permission d'acceder à la page
                // on envoie le header "403 Forbidden"
                http_response_code(403);
                $this->show('error/err403');
                exit();
            }
        } else {
            // si l'utilisateur n'est PAS connecté
            header('Location: ' . $router->generate('user-login'));
            exit();
        }
    }
}
