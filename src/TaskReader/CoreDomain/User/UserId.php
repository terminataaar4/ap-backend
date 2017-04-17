<?php

namespace TaskReader\CoreDomain\User;

class UserId
{
    private $value;

    public function __construct($value)
    {
        $this->value = (string) $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqualTo(UserId $userId)
    {
        return $this->getValue() === $userId->getValue();
    }
}
