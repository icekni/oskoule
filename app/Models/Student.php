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
    public static function findAll(): array
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

    /**
     * Methode find
     * Elle permet de retourner toutes les infos de l'etudiant dont l'id est passé en argument
     *
     * @param [type] $studentId est l'id de l'etudiant
     * @return object ou bool
     */
    public static function find($studentId)
    {
        // On se connecte a la DB
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            SELECT *
            FROM student
            WHERE student.id = :id;
        ';

        // On utilise prepare car il y a une variable manipulable
        $query = $pdo->prepare($sql);

        // On bind le token
        $query->bindValue(':id', $studentId, PDO::PARAM_INT);

        // On execute la requete
        $query->execute();

        // On fetch pour obtenir un objet
        $result = $query->fetchObject(static::class);

        return $result;
    }

    /**
     * Methode insert
     * Pour inserer dans la base de donnée un etudiant
     *
     * @return boolean true si l'insertion s'est bien passée, sinon false
     */
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
                :teacher_id
            );
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);
        $query->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);

        // Puis on execute la requete
        $query->execute();

        // Si au moin une ligne a été ecrite, c'est que la requete a réussi
        return ($query->rowCount() > 0);
    }

    /**
     * Methode update
     * Met a jour un etudiant dans la BDD
     *
     * @return boolean
     */
    public function update(): bool
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            UPDATE student 
            SET
                firstname = :firstname,
                lastname = :lastname,
                status = :status,
                teacher_id = :teacher_id,        
                updated_at = NOW()
            WHERE id = :id;
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $query->bindValue(':teacher_id', $this->teacher_id, PDO::PARAM_INT);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

        // Puis on execute la requete
        $query->execute();

        // Si au moin une ligne a été ecrite, c'est que la requete a réussi
        return ($query->rowCount() > 0);
    }

    /**
     * Methode delete
     * Supprime un etudiant de la BDD
     *
     * @return boolean
     */
    public function delete(): bool
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            DELETE FROM student 
            WHERE id = :id;
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

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
