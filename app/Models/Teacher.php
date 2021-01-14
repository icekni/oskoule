<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Teacher extends CoreModel
{
    private $job;

    // ========================================
    // Methodes specifiques
    // ========================================

    /**
     * Methode findAll
     * Elle retourne un tableau contenant tout les profs
     *
     * @return array
     */
    public static function findAll() : array
    {
        // On se connecte a la DB
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            SELECT *
            FROM teacher;
        ';

        // J'ai une variable, mais l'utilisateur ne devrait pas y avoir acces, donc pas besoin de prepare
        // Je dois par contre query car j'attends de recevoir des données
        $pdoStatement = $pdo->query($sql);

        // Puis on fetchAll pour obtenir un tableau contenant des objet
        $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS, static::class);

        return $result;
    }
    
    public function insert()
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            INSERT INTO teacher (
                firstname,
                lastname,
                job,
                status
            ) VALUES (
                :firstname,
                :lastname,
                :job,
                :status
            );
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $query->bindValue(':job', $this->job, PDO::PARAM_STR);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);

        // Puis on execute la requete
        $query->execute();

        // Si au moin une ligne a été ecrite, c'est que la requete a réussi
        return ($query->rowCount() > 0);
    }

    // ========================================
    // Getters & Setters
    // ========================================

    /**
     * Get the value of job
     */ 
    public function getJob()
    {
        return $this->job;
    }

    /**
     * Set the value of job
     *
     * @return  self
     */ 
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }
}
