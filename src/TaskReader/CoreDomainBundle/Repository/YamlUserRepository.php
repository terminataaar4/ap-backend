<?php

namespace TaskReader\CoreDomainBundle\Repository;

use TaskReader\CoreDomain\User\User;
use TaskReader\CoreDomain\User\UserId;
use TaskReader\CoreDomain\User\UserRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class YamlUserRepository implements UserRepository
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;

        (new Filesystem())->touch($this->filename);
    }

    /**
     * {@inheritDoc}
     */
    public function find(UserId $userId)
    {
        foreach ($this->findAll() as $user) {
            if ($user->getId()->isEqualTo($userId)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll()
    {
        $users = array();
        foreach ($this->getRows() as $row) {
            $users[] = new User(
                new UserId($row['id']),
                $row['email'],
                $row['password']
            );
        }

        return $users;
    }

    /**
     * {@inheritDoc}
     */
    public function add(User $user)
    {
        $rows = array();
        foreach ($this->getRows() as $row) {
            if ($user->getId()->isEqualTo(new UserId($row['id']))) {
                continue;
            }

            $rows[] = $row;
        }

        $rows[] = array(
            'id'         => $user->getId()->getValue(),
            'email' => $user->getEmail(),
            'password'  => $user->getPassword(),
        );

        file_put_contents($this->filename, Yaml::dump($rows));
    }

    /**
     * {@inheritDoc}
     */
    public function remove(User $user)
    {
        $rows = array();
        foreach ($this->getRows() as $row) {
            if ($user->getId()->isEqualTo(new UserId($row['id']))) {
                continue;
            }

            $rows[] = $row;
        }

        file_put_contents($this->filename, Yaml::dump($rows));
    }

    private function getRows()
    {
        return Yaml::parse($this->filename) ?: array();
    }
}
