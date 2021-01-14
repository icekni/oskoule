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
}