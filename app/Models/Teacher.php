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
    public static function findAll(): array
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

    /**
     * Methode find
     * Elle permet de retourner toutes les infos du prof dont l'id est passé en argument
     *
     * @param [type] $teacherId est l'id du prof
     * @return object ou bool
     */
    public static function find($teacherId)
    {
        // On se connecte a la DB
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            SELECT *
            FROM teacher
            WHERE teacher.id = :id;
        ';

        // On utilise prepare car il y a une variable manipulable
        $query = $pdo->prepare($sql);

        // On bind le token
        $query->bindValue(':id', $teacherId, PDO::PARAM_INT);

        // On execute la requete
        $query->execute();

        // On fetch pour obtenir un objet
        $result = $query->fetchObject(static::class);

        return $result;
    }

    /**
     * Methode insert
     * Pour inserer dans la base de donnée un prof
     *
     * @return boolean true si l'insertion s'est bien passée, sinon false
     */
    public function insert(): bool
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

    /**
     * Methode update
     * Met a jour un prof dans la BDD
     *
     * @return boolean
     */
    public function update(): bool
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            UPDATE teacher 
            SET
                firstname = :firstname,
                lastname = :lastname,
                job = :job,
                status = :status,                
                updated_at = NOW()
            WHERE id = :id;
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':firstname', $this->firstname, PDO::PARAM_STR);
        $query->bindValue(':lastname', $this->lastname, PDO::PARAM_STR);
        $query->bindValue(':job', $this->job, PDO::PARAM_STR);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

        // Puis on execute la requete
        $query->execute();

        // Si au moin une ligne a été ecrite, c'est que la requete a réussi
        return ($query->rowCount() > 0);
    }

    /**
     * Methode delete
     * Supprime un prof de la BDD
     *
     * @return boolean
     */
    public function delete(): bool
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            DELETE FROM teacher 
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
