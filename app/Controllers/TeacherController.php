<?php

namespace App\Controllers;

use App\Models\Teacher;

class TeacherController extends CoreController
{
    /**
     * Methode list
     * Affiche la liste des profs
     *
     * @return void
     */
    public function list() : void
    {
        // On va devoir recuperer la liste des profs
        // Pour ca il faudra utiliser la methode findAll qui nous retournera un tableau contenant a chaque index un prof
        $teachers = Teacher::findAll();

        // On doit ensuite transmettre les donénes recuperees a la vue
        // On utilisera pour ca le 2eme argument de la methode show
        // Pour plus de clarté, je vais utiliser une variable intermediaire $viewVars
        $viewVars = [
            'teachers' => $teachers,
        ];        

        $this->show('teacher/list', $viewVars);
    }

    /**
     * Methode add
     * Elle affiche le formulaire d'ajout d'un prof
     *
     * @return void
     */
    public function add() : void
    {
        // Il faut afficher la page pour ajouter un prof

        // J'affiche la page
        $this->show('teacher/add');
    }

    /**
     * Methode addPost
     * Elle recoit le formulaire d'ajout et insere dans la BDD
     *
     * @return void
     */
    public function addPost() : void
    {
        // On commence par récupérer et filtrer les données du formulaire
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
        $job = filter_input(INPUT_POST, 'job', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

        // On créé un tableau qui recevra les erreurs
        $errorList = [];

        // On verifie que les champs ne sont pas vides
        if (empty($firstname)) {
            $errorList['prenom'] = 'Le champ Prénom n\'est pas correctement remplit';
        }
        if (empty($lastname)) {
            $errorList['nom'] = 'Le champ Nom n\'est pas correctement remplit';
        }
        if (empty($job)) {
            $errorList['job'] = 'Le champ Titre n\'est pas correctement remplit';
        }
        if (empty($status)) {
            $errorList['status'] = 'Le champ Status n\'est pas correctement remplit';
        }

        // S'il n'y a pas eu d'erreur jusque là, on peut inserer dans la BDD
        if (empty($errorList)) {
            // On commence par créé un nouvel objet Teacher
            $teacher = new Teacher();

            // On lui definit ses propriétés
            $teacher->setFirstname($firstname);
            $teacher->setLastname($lastname);
            $teacher->setJob($job);
            $teacher->setStatus($status);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($teacher->save()) {
                // Si ca a reussi, on redirige vers la liste des profs
                $this->list();
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
            $this->show('teacher/add', $viewVars);
        }
    }
}