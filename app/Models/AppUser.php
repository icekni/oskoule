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
     * @return object
     */
    public static function findByEmail($email) : object
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