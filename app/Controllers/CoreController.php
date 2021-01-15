<?php

namespace App\Controllers;

abstract class CoreController
{
    // Je créé une propriete $token qui recevra le CSRF token
    private $token;

    public function __construct()
    {
        global $match;
        // Je recupere le nom de la route
        $routeName = $match['name'];
        //? j'ai aussi pensé a utiliser setControllersArguments, methode d'altoDispatcher, pour passer $match en argument au construct du CoreController, ca me permettrait d'eviter de faire global $match
        //? Je me demande meme si on pourrait pas passer $router aussi et le stocker dans une propriete pour eviter le global $router partout

        // Je profite du global $match pour tenter de recuperer mon token passé en GET (pour delete), ou si ce n'est pas dans $match, je regarde dans le POST
        // Si jamais il n'est pas passé ni via GET, ni via POST, filter_input me renverra null donc pas d'erreur a prevoir
        $this->token = isset($match['params']['token']) ? $match['params']['token'] : filter_input(INPUT_POST, 'token');

        // ========================================
        // Gestion des acces par role de l'utilisateur
        // ========================================

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
            'user-list' => ['admin'],
            'user-add' => ['admin'],
            'user-addpost' => ['admin'],
            'user-delete' => ['admin'],
        ];

        // On doit verifier si le nom de la route en cours est bien dans le tableau
        if (array_key_exists($routeName, $acl)) {
            // Si oui, on recupere les roles autorisés pour cette page
            $authorizedRoles = $acl[$routeName];
            // Et on utilise la methode checkAuthorization pour verifier si l'utilisateur est autorisé a acceder a la page
            $this->checkAuthorization($authorizedRoles);
        }

        // ========================================
        // Anti CSRF
        // ========================================

        // Definition des pages a proteger
        $csrfTokenToCheck = [
            'user-login',
            'teacher-addpost',
            'teacher-editpost',
            'teacher-delete',
            'student-addpost',
            'student-editpost',
            'student-delete',
            'user-addpost',
            'user-editpost',
            'user-delete',
        ];

        // Si la page en cours est dans le tableau, alors on verifie le token
        if (in_array($routeName, $csrfTokenToCheck)) {
            $this->checkCSRFToken();
        }

        // Plutot que generer le token dans chaque page qui mene à une methode à proteger, je suis faignant et je le fais pour toutes les pages en une seule fois, via le construct du CoreController
        // Il sera re-generer a chaque page, meme celles non protegées, mais c'est pas grave
        // Le check ne se fera que sur celles dans le tableau $csrfTokenToCheck
        // Mais meme si le check se faisait sur toutes les pages, ca n'en serait que plus securisé
        // Bien sur je le fait apres le check, sinon le token en session aura changé avant le check et ne correspondra plus au token passé en GET ou POST
        $this->generateCSRFToken();
    }

    /**
     * Verification du token CSRF
     *
     * @return void
     */
    public function checkCSRFToken(): void
    {
        // Je recupere le token passé en session
        // En cas d'inactivité, l'index csrfToken disparait de $_SESSION, donc dans ce cas, je renvois null, ca provoquera une erreur 403 et l'utilisateur devra se reconnecter
        $sessionToken = isset($_SESSION['csrfToken']) ? $_SESSION['csrfToken'] : null;

        // Je dois le comparer avec le token que j'ai recupéré dans $this->token
        // Je dois verifier s'ils ne sont pas vides, car null = null et ca validerait la verification
        if ($sessionToken !== $this->token || empty($sessionToken) || empty($this->token)) {
            http_response_code(403);
            $this->show('error/err403');
            exit();
        }
    }

    /**
     * Methode pour generer un CSRF token
     *
     * @return string qui correspond au token
     */
    protected function generateCSRFToken(): string
    {
        $byte = random_bytes(5);
        $token = bin2hex($byte);

        $_SESSION['csrfToken'] = $token;

        return $token;
    }

    /**
     * Methode show
     * Elle se charge de faire les require des templates necessaires pour afficher la page indiquée dans $viewName
     *
     * @param string $viewName est le nom de la vue a afficher, sous la forme 'dossier/fichierTPL
     * @param array $viewVars contient les données a transmettre a la page et est optionnel
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
    protected function checkAuthorization($roles = []): bool
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
