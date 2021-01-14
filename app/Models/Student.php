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
