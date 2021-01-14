<?php

namespace App\Controllers;

abstract class CoreController
{
    /**
     * Methode show
     * Elle se charge de faire les require des template necessaire pour afficher la page indiquée dans $viewName
     *
     * @param string $viewName est le nom de la vue a afficher, sous la forme 'dossier/fichierTPL
     * @param array $viewVars contient les données a transmettre a la page
     * @return void
     */
    protected function show(string $viewName, $viewVars = []) : void
    {
        // On stocke le nom de la vue courante dans $viewVars
        $viewVars['currentPage'] = $viewName;

        // Pour avoir un acces plus pratique aux viewVars, on utilise extract qui creera une variable du nom de l'index utilisé dans $viewVars
        extract($viewVars);

        require __DIR__ . '/../Views/layout/header.tpl.php';
        require __DIR__ . '/../Views/' . $viewName . '.tpl.php';
        require __DIR__ . '/../Views/layout/footer.tpl.php';
    }
}
