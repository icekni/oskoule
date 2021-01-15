<?php

namespace App\Controllers;

class MainController extends CoreController
{
    /**
     * Methode Home
     * Affiche la page home
     *
     * @return void
     */
    public function home() : void
    {
        // La home n'a pas besoin d'avoir de donnée fournie, donc on l'appelle telle quelle
        $this->show('main/home');
    }

    public function test()
    {
        # code...
    }
}