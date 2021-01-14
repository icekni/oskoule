<?php

namespace App\Controllers;

use App\Models\AppUser;

class UserController extends CoreController
{
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

    public function loginpost()
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

}