<?php

namespace App\Controllers;

use App\Models\AppUser;

class UserController extends CoreController
{
    /**
     * Methode list
     * Affiche la liste des users
     *
     * @return void
     */
    public function list() : void
    {
        // On va devoir recuperer la liste des users
        // Pour ca il faudra utiliser la methode findAll qui nous retournera un tableau contenant a chaque index un prof
        $users = AppUser::findAll();

        // On doit ensuite transmettre les donénes recuperees a la vue
        // On utilisera pour ca le 2eme argument de la methode show
        // Pour plus de clarté, je vais utiliser une variable intermediaire $viewVars
        $viewVars = [
            'users' => $users,
        ];        

        $this->show('user/list', $viewVars);
    }

    /**
     * Methode add
     * Elle affiche le formulaire d'ajout d'un user
     *
     * @return void
     */
    public function add() : void
    {
        // Il faut afficher la page pour ajouter un user

        // Et j'affiche la page
        $this->show('user/add');
    }

    /**
     * Methode addPost
     * Elle recoit le formulaire d'ajout et insere dans la BDD
     *
     * @return void
     */
    public function addPost() : void
    {
        global $router;

        // On commence par récupérer et filtrer les données du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

        // On créé un tableau qui recevra les erreurs
        $errorList = [];

        // On verifie que les champs ne sont pas vides
        // Le filtre VALIDATE sur le champ email me permet de renvoyer false s'il y a des caracteres non autorisé
        if (empty($email) || !$email) {
            $errorList['email'] = 'Le champ Email n\'est pas correctement remplit';
        }
        if (empty($name)) {
            $errorList['nom'] = 'Le champ Nom n\'est pas correctement remplit';
        }
        // Le champ password devra contenir 8 catacteres mini, dont au moins 1 majuscule, 1 minuscule et 1 caractere parmis 
        if (empty($password) || !preg_match('`^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[-_|%&*=@$])[-_|%&*=@$a-zA-Z0-9]{8,}$`', $password)) {
            $errorList['password'] = 'Le champ Password n\'est pas correctement remplit';
        }
        // Le champ role ne peut contenir que user ou admin
        if (empty($role) || !preg_match('`user|admin`', $role)) {
            $errorList['role'] = 'Le champ Role n\'est pas correctement remplit';
        }
        if (empty($status) || !preg_match('`1|2`', $status)) {
            $errorList['status'] = 'Le champ Status n\'est pas correctement remplit';
        }

        // On verifie s'il n'existe pas deja un utilisateur avec cet email
        if (AppUser::findByEmail($email)) {
            $errorList['autre'] = 'Un utilisateur avec cet email existe déjà';
        }

        // S'il n'y a pas eu d'erreur jusque là, on peut inserer dans la BDD
        if (empty($errorList)) {
            // On commence par créé un nouvel objet AppUser
            $user = new AppUser();

            // On lui definit ses propriétés
            $user->setEmail($email);
            // On insert directement le password haché grace a password_hash
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setName($name);
            $user->setRole($role);
            $user->setStatus($status);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($user->save()) {
                // Si ca a reussi, on redirige vers la liste des users
                // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
                header('Location: ' . $router->generate('user-list'));
            }
            else {
                // Sinon on ajoute une erreur dans $errorList et on affiche le formulaire d'ajout
                $errorList['autre'] = 'L\'insertion en base de données à échouee';
            }
        }

        // S'il y a eu des erreurs, alors on redirige vers la page de login
        if (!empty($errorList)) {
            // On stocke les erreurs dans $viewVars
            $viewVars = [
                'errors' => $errorList,
            ];

            // On affiche la page de login
            $this->show('user/add', $viewVars);
        }
        
    }

    /**
     * Methode edit
     * Affiche la page d'edition d'un user
     *
     * @param [type] $userId est l'id du user a editer
     * @return void
     */
    public function edit($userId) : void
    {
        // On doit afficher la vue user/add, mais avec les champs pre-remplit
        // On commence par aller chercher les infos du user dont l'id est passé dans l'url
        $user = AppUser::find($userId);

        // On transmet a la vue
        $viewVars = [
            'user' => $user,
        ];

        $this->show('user/add', $viewVars);
    }

    /**
     * Methode editPost
     * Recoit le formulaire d'edition d'un user
     *
     * @param [type] $userId est l'id du user a editer
     * @return void
     */
    public function editPost($userId) : void
    {
        global $router;

        // On commence par récupérer et filtrer les données du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

        // On créé un tableau qui recevra les erreurs
        $errorList = [];

        // On verifie que les champs ne sont pas vides
        // Le filtre VALIDATE sur le champ email me permet de renvoyer false s'il y a des caracteres non autorisé
        if (empty($email) || !$email) {
            $errorList['email'] = 'Le champ Email n\'est pas correctement remplit';
        }
        if (empty($name)) {
            $errorList['nom'] = 'Le champ Nom n\'est pas correctement remplit';
        }
        // Le champ password devra contenir 8 catacteres mini, dont au moins 1 majuscule, 1 minuscule et 1 caractere parmis 
        if (empty($password) || !preg_match('`^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[-_|%&*=@$])[-_|%&*=@$a-zA-Z0-9]{8,}$`', $password)) {
            $errorList['password'] = 'Le champ Password n\'est pas correctement remplit';
        }
        // Le champ role ne peut contenir que user ou admin
        if (empty($role) || !preg_match('`user|admin`', $role)) {
            $errorList['role'] = 'Le champ Role n\'est pas correctement remplit';
        }
        if (empty($status) || !preg_match('`1|2`', $status)) {
            $errorList['status'] = 'Le champ Status n\'est pas correctement remplit';
        }

        // S'il n'y a pas eu d'erreur jusque là, on peut inserer dans la BDD
        if (empty($errorList)) {
            // On commence par créé un nouvel objet AppUser pre-remplit avec les données de la BDD (pour avoir l'id)
            $user = AppUser::findByEmail($email);

            // On lui definit ses propriétés
            $user->setEmail($email);
            // On insert directement le password haché grace a password_hash
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $user->setName($name);
            $user->setRole($role);
            $user->setStatus($status);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($user->save()) {
                // Si ca a reussi, on redirige vers la liste des users
                // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
                header('Location: ' . $router->generate('user-list'));
            }
            else {
                // Sinon on ajoute une erreur dans $errorList et on affiche le formulaire d'ajout
                $errorList['autre'] = 'L\'insertion en base de données à échouee';
            }
        }

        // S'il y a eu des erreurs, alors on redirige vers la page de login
        if (!empty($errorList)) {
            // On stocke les erreurs dans $viewVars
            $viewVars = [
                'errors' => $errorList,
            ];

            // On affiche la page de login
            $this->show('user/add', $viewVars);
        }
    }

    /**
     * Methode delete
     * Permet de supprimer un user dont l'id est en argument
     *
     * @param [type] $userId
     * @return void
     */
    public function delete($userId) : void
    {
        global $router;

        // On créé un AppUser pre-remplit
        $user = AppUser::find($userId);        

        // On appelle la methode delete de AppUser
        $user->delete();

        // On redirige vers la liste
        // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
        header('Location: ' . $router->generate('user-list'));
    }

    /**
     * Methode login
     * Permet la connexion au backoffice
     *
     * @return void
     */
    public function login() : void
    {
        // Affichage de la page de connexion
        $this->show('user/login');
    }

    /**
     * Methode loginpost
     * Recoit les infos du formulaire de connexion
     *
     * @return void
     */
    public function loginpost() : void
    {
        // On commence par recuperer les données du formulaire
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        // Sans filtre pour password pour ne pas encoder les caracteres speciaux
        $password = filter_input(INPUT_POST, 'password');        

        // On prepare notre tableau d'erreur
        $errorList =[];

        // Verifications du bon remplissage des champs
        if (empty($email)) {
            $errorList['email'] = 'Le champ Email n\'est pas correctement remplit';
        }
        if (empty($password)) {
            $errorList['password'] = 'Le champ Password n\'est pas correctement remplit';
        }

        // On verifie s'il existe bien un utilisateur avec ces données
        $user = AppUser::findByEmail($email);

        // Si ûser est a false, c'est que la requete n'a pas trouvé d'utilisateur avec cet email
        // On ne rentre pas dans le detail pour dire que c'est l'email qui est mauvais pour ne pas donner d'indice a un petit malin
        if (!$user) {
            $errorList['autre'] = 'Données de connexion incorrecte';
        }

        // S'il n'y a pas d'erreur jusque là, on peut aller verifier le mot de passe
        if (empty($errorList)) {
            // On verifie si le mot de passe saisi dans le formulaire correspond bien a $user
            if (password_verify($password, $user->getPassword())) {
                // On stocke en session $user et son id
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userObject'] = $user;

                // Et on peut rediriger vers la home
                $this->show('main/home');
            }
            // Sinon c'est que le mot de passe ne correspond pas
            else {
                $errorList['autre'] = 'Données de connexion incorrecte';
            }
        }

        // S'il y a eu des erreurs, alors on redirige vers la page de login
        if (!empty($errorList)) {
            // On stocke les erreurs dans $viewVars
            $viewVars = [
                'errors' => $errorList,
            ];

            // On affiche la page de login
            $this->show('user/login', $viewVars);
        }
    }

    public function logout()
    {
        // Il faut enlever de la session l'id de l'user et l'objet user
        unset($_SESSION['userId']);
        unset($_SESSION['userObject']);

        // On prepare un message a afficher
        $errorList['autre'] = 'Vous avez été déconnecté';

        // On transmet a la vue via $viewVars
        $viewVars = [
            'errors' => $errorList,
        ];

        // Puis on redirige vers la page de connexion
        $this->show('user/login', $viewVars);
    }
}