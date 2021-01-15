<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

abstract class CoreModel
{
    // Les profs comme les eleves ont un id, un firstname, un lastname et un status, on va donc pouvoir les mutualiser grace au CoreModel
    //? ca n'est plus vrai depuis la gestion des users, mais c'est pas grave, ca fera juste des propriétés vides
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $status;

    public function save()
    {
        if ($this->id != null) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    // ========================================
    // Getters & Setters
    // ========================================

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
