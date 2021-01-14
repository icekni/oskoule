<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

abstract class CoreModel
{
    // Les profs comme les eleves ont un id, un firstname et un lastname, on va donc pouvoir les mutualiser grace au CoreModel
    protected $id;
    protected $firstname;
    protected $lastname;

    /**
     * Methode findAll
     * Elle retourne un tableau contenant tout les *** (en focntion de la classe qui l'appelle)
     *
     * @return array
     */
    public static function findAll() : array
    {
        // A cause de la facto de la fonction, je dois recuperer le nom de la classe qui l'appelle
        $classname = strtolower(preg_replace('`^(.*\\\\)`', '', static::class));

        // On se connecte a la DB
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            SELECT *
            FROM ' . $classname . ';
        ';

        // J'ai une variable, mais l'utilisateur ne devrait pas y avoir acces, donc pas besoin de prepare
        // Je dois par contre query car j'attends de recevoir des données
        $pdoStatement = $pdo->query($sql);

        // Puis on fetchAll pour obtenir un tableau contenant des objet
        $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS, static::class);

        return $result;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }
}
