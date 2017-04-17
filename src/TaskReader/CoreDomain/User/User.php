<?php

namespace TaskReader\CoreDomain\User;

class User
{
    private $id;

    private $email;

    private $password;

    public function __construct(UserId $id, $email, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}
