<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class AppUser extends CoreModel
{
    private $email;
    private $name;
    private $password;
    private $role;

    // ========================================
    // Methodes specifiques
    // ========================================

    /**
     * Methode findAll
     * Elle retourne un tableau contenant tout les users
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
            FROM app_user;
        ';

        // J'ai une variable, mais l'utilisateur ne devrait pas y avoir acces, donc pas besoin de prepare
        // Je dois par contre query car j'attends de recevoir des données
        $pdoStatement = $pdo->query($sql);

        // Puis on fetchAll pour obtenir un tableau contenant des objet
        $result = $pdoStatement->fetchAll(PDO::FETCH_CLASS, static::class);

        return $result;
    }
    
    /**
     * Methode findByEmail
     * Cherche si un utilisateur correspond à l'email passé en argument
     *
     * @param [type] $email correspond à l'email de l'user recherché
     * @return object ou bool
     */
    public static function findByEmail($email)
    {
        // On se connecte a la DB
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = "
            SELECT *
            FROM `app_user`
            WHERE email = :email;
        ";

        // Vu qu'il y a des variables modifiables par l'utilisateur, on prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens avec un filtre pour eviter les injections SQL
        $query->bindValue(':email', $email, PDO::PARAM_STR);

        // On execute la requete
        $query->execute();

        // On fetch pour obtenir un seul objet
        $result = $query->fetchObject(self::class);

        return $result;
    }

    /**
     * Methode find
     * Elle permet de retourner toutes les infos de l'user dont l'id est passé en argument
     *
     * @param [type] $userId est l'id de l'user
     * @return object ou bool
     */
    public static function find($userId)
    {
        // On se connecte a la DB
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            SELECT *
            FROM app_user
            WHERE app_user.id = :id;
        ';

        // On utilise prepare car il y a une variable manipulable
        $query = $pdo->prepare($sql);

        // On bind le token
        $query->bindValue(':id', $userId, PDO::PARAM_INT);

        // On execute la requete
        $query->execute();

        // On fetch pour obtenir un objet
        $result = $query->fetchObject(static::class);

        return $result;
    }
    
    /**
     * Methode insert
     * Pour inserer dans la base de donnée un user
     *
     * @return boolean true si l'insertion s'est bien passée, sinon false
     */
    public function insert()
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            INSERT INTO app_user (
                email,
                name,
                password,
                role,
                status
            ) VALUES (
                :email,
                :name,
                :password,
                :role,
                :status
            );
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        // Normalement notre mot de passe sera deja filtré par la regex, donc pas besoin de filtre
        $query->bindValue(':password', $this->password);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);

        // Puis on execute la requete
        $query->execute();

        // Si au moin une ligne a été ecrite, c'est que la requete a réussi
        return ($query->rowCount() > 0);
    }

    /**
     * Methode update
     * Met a jour un user dans la BDD
     *
     * @return boolean
     */
    public function update(): bool
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            UPDATE app_user 
            SET
                email = :email,
                name = :name,
                password = :password,
                role = :role,
                status = :status,       
                updated_at = NOW()
            WHERE id = :id;
        ';

        // La requete contient des champs qui pourraient etre manipulés par l'utilisateur, donc prepare
        $query = $pdo->prepare($sql);

        // On bind les tokens en prenant soin d'appliquer les bons filtres
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':name', $this->name, PDO::PARAM_STR);
        // Normalement notre mot de passe sera deja filtré par la regex, donc pas besoin de filtre
        $query->bindValue(':password', $this->password);
        $query->bindValue(':role', $this->role, PDO::PARAM_STR);
        $query->bindValue(':status', $this->status, PDO::PARAM_INT);
        $query->bindValue(':id', $this->id, PDO::PARAM_INT);

        // Puis on execute la requete
        $query->execute();

        // Si au moin une ligne a été ecrite, c'est que la requete a réussi
        return ($query->rowCount() > 0);
    }

    /**
     * Methode delete
     * Supprime un user de la BDD
     *
     * @return boolean
     */
    public function delete(): bool
    {
        // On se connecte a la BDD
        $pdo = Database::getPDO();

        // On ecrit la requete
        $sql = '
            DELETE FROM app_user 
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
    // GEtters & Setters
    // ========================================

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
}