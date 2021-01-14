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
}