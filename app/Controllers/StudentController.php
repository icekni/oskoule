<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Teacher;

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
        // Pour dynamiser le select correspondant aux profs, il faut recuperer tout les profs
        $teachers = Teacher::findAll();

        // Puis les passer a la vue
        $viewVars = [
            'teachers' => $teachers,
        ];

        // Et j'affiche la page
        $this->show('student/add', $viewVars);
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
        if (empty($status) || !preg_match('`1|2`', $status)) {
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
            $student->setTeacher_id($teacher);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($student->save()) {
                // Si ca a reussi, on redirige vers la liste des etudiants
                // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
                header('Location: ' . $router->generate('student-list'));
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

    /**
     * Methode edit
     * Affiche la page d'edition d'un etudiant
     *
     * @param [type] $studentId est l'id du etudiant a editer
     * @return void
     */
    public function edit($studentId) : void
    {
        // On doit afficher la vue student/add, mais avec les champs pre-remplit
        // On commence par aller chercher les infos du etudiant dont l'id est passé dans l'url
        $student = Student::find($studentId);
        
        // Pour dynamiser le select correspondant aux profs, il faut recuperer tout les profs
        $teachers = Teacher::findAll();

        // On transmet a la vue
        $viewVars = [
            'student' => $student,
            'teachers' => $teachers,
        ];

        $this->show('student/add', $viewVars);
    }

    /**
     * Methode editPost
     * Recoit le formulaire d'edition d'un etudiant
     *
     * @param [type] $studentId est l'id du etudiant a editer
     * @return void
     */
    public function editPost($studentId) : void
    {
        global $router;

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
        if (empty($status) || !preg_match('`1|2`', $status)) {
            $errorList['status'] = 'Le champ Status n\'est pas correctement remplit';
        }
        if (empty($teacher)) {
            $errorList['teacher'] = 'Le champ Prof n\'est pas correctement remplit';
        }

        // On commence par créé un nouvel objet Student
        $student = Student::find($studentId);

        // S'il n'y a pas eu d'erreur jusque là, on peut inserer dans la BDD
        if (empty($errorList)) {

            // On lui definit ses propriétés
            $student->setFirstname($firstname);
            $student->setLastname($lastname);
            $student->setStatus($status);
            $student->setTeacher_id($teacher);

            // Puis on insere dans la BDD avec une condition pour verifier la reussite de la requete
            if ($student->save()) {
                // Si ca a reussi, on redirige vers la liste des etudiants
                // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
                header('Location: ' . $router->generate('student-list'));
            }
            else {
                // Sinon on ajoute une erreur dans $errorList et on affiche le formulaire d'ajout
                $errorList['autre'] = 'L\'insertion en base de données à échouee';
            }
        }

        // S'il y a eu des erreurs, alors on redirige vers la page de login
        if (!empty($errorList)) {
            // il va falloir acceder la la liste des profs pour re-afficher le select
            $teachers = Teacher::findAll();

            // On stocke les erreurs dans $viewVars
            $viewVars = [
                'errors' => $errorList,
                'student' => $student,
                'teachers' => $teachers,

            ];

            // On affiche la page de login
            $this->show('student/add', $viewVars);
        }
    }

    /**
     * Methode delete
     * Permet de supprimer un etudiant dont l'id est en argument
     *
     * @param [type] $studentId
     * @return void
     */
    public function delete($studentId) : void
    {
        global $router;

        // On créé un Student pre-remplit
        $student = Student::find($studentId);        

        // On appelle la methode delete de Student
        $student->delete();

        // On redirige vers la liste
        // Via header, car si je me contente de faire un show ou $this->list, le moindre F5 renverra la requete et provoquera surement une erreur
        header('Location: ' . $router->generate('student-list'));
    }
}