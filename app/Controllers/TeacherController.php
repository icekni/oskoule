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
        global $router;

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
        if (empty($status) || !preg_match('`[1-2]`', $status)) {
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
                // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
                header('Location: ' . $router->generate('teacher-list'));
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

    /**
     * Methode edit
     * Affiche la page d'edition d'un prof
     *
     * @param [type] $teacherId est l'id du prof a editer
     * @return void
     */
    public function edit($teacherId) : void
    {
        // On doit afficher la vue teacher/add, mais avec les champs pre-remplit
        // On commence par aller chercher les infos du prof dont l'id est passé dans l'url
        $teacher = Teacher::find($teacherId);

        // On transmet a la vue
        $viewVars = [
            'teacher' => $teacher,
        ];

        $this->show('teacher/add', $viewVars);
    }

    /**
     * Methode editPost
     * Recoit le formulaire d'edition d'un prof
     *
     * @param [type] $teacherId est l'id du prof a editer
     * @return void
     */
    public function editPost($teacherId) : void
    {
        global $router;

        // On recupere les données du formulaire        
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
            // On commence par créé un nouvel objet Teacher pre-remplit avec les données existantes
            $teacher = Teacher::find($teacherId);

            // On lui definit ses propriétés
            $teacher->setFirstname($firstname);
            $teacher->setLastname($lastname);
            $teacher->setJob($job);
            $teacher->setStatus($status);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($teacher->save()) {
                // Si ca a reussi, on redirige vers la liste des profs
                // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
                header('Location: ' . $router->generate('teacher-list'));
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

    /**
     * Methode delete
     * Permet de supprimer un prof dont l'id est en argument
     *
     * @param [type] $teacherId
     * @return void
     */
    public function delete($teacherId) : void
    {
        global $router;

        // On créé un Teacher pre-remplit
        $teacher = Teacher::find($teacherId);        

        // On appelle la methode delete de Teacher
        $teacher->delete();

        // On redirige vers la liste
        // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
        header('Location: ' . $router->generate('teacher-list'));
    }
}