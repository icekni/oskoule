<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Student extends CoreModel
{
    private $teacher_id;

    // ========================================
    // Methodes specifiques
    // ========================================

    /**
     * Methode findAll
     * Elle retourne un tableau contenant tout les etudiants
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
            FROM student;
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
            INSERT INTO student (
                firstname,
                lastname,
                status,
                teacher_id
            ) VALUES (
                :firstname,
                :lastname,
                :status,
                teacher_id
            );
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
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
     * Get the value of teacher_id
     */ 
    public function getTeacher_id()
    {
        return $this->teacher_id;
    }

    /**
     * Set the value of teacher_id
     *
     * @return  self
     */ 
    public function setTeacher_id($teacher_id)
    {
        $this->teacher_id = $teacher_id;

        return $this;
    }
}
