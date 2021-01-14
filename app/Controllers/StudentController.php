<?php

namespace App\Controllers;

use App\Models\Student;

class StudentController extends CoreController
{
    /**
     * Methode list
     * Affiche la liste des etudiants
     *
     * @return void
     */
    public function list() : void
    {
        // On va devoir recuperer la liste des etudiants
        // Pour ca il faudra utiliser la methode findAll qui nous retournera un tableau contenant a chaque index un prof
        $students = Student::findAll();

        // On doit ensuite transmettre les donénes recuperees a la vue
        // On utilisera pour ca le 2eme argument de la methode show
        // Pour plus de clarté, je vais utiliser une variable intermediaire $viewVars
        $viewVars = [
            'students' => $students,
        ];        

        $this->show('student/list', $viewVars);
    }

    /**
     * Methode add
     * Elle affiche le formulaire d'ajout d'un etudiant
     *
     * @return void
     */
    public function add() : void
    {
        // Il faut afficher la page pour ajouter un etudiant

        // Et j'affiche la page
        $this->show('student/add');
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
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);
        $teacher = filter_input(INPUT_POST, 'teacher', FILTER_SANITIZE_NUMBER_INT);

        // On créé un tableau qui recevra les erreurs
        $errorList = [];

        // On verifie que les champs ne sont pas vides
        if (empty($firstname)) {
            $errorList['prenom'] = 'Le champ Prénom n\'est pas correctement remplit';
        }
        if (empty($lastname)) {
            $errorList['nom'] = 'Le champ Nom n\'est pas correctement remplit';
        }
        // Le champ status devra avoir comme valeur, soit 1, soit 2
        if (empty($status) || preg_match('`[1-2]`', $status)) {
            $errorList['status'] = 'Le champ Status n\'est pas correctement remplit';
        }
        if (empty($teacher)) {
            $errorList['teacher'] = 'Le champ Prof n\'est pas correctement remplit';
        }

        // S'il n'y a pas eu d'erreur jusque là, on peut inserer dans la BDD
        if (empty($errorList)) {
            // On commence par créé un nouvel objet Student
            $student = new Student();

            // On lui definit ses propriétés
            $student->setFirstname($firstname);
            $student->setLastname($lastname);
            $student->setStatus($status);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($student->save()) {
                // Si ca a reussi, on redirige vers la liste des etudiants
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
            $this->show('student/add', $viewVars);
        }
        
    }
}