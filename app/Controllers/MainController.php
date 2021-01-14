<?php

namespace App\Controllers;

class MainController extends CoreController
{
    public function home() : void
    {
        // La home n'a pas besoin d'avoir de données fournie, donc on l'appelle telle quel
        $this->show('main/home');
    }
}