<?php

namespace TaskReader\CoreDomainBundle\Tests\Repository;

use org\bovigo\vfs\vfsStream;
use TaskReader\CoreDomainBundle\Repository\YamlUserRepository;
use PHPUnit\Framework\TestCase;
use TaskReader\CoreDomain\User\User;
use TaskReader\CoreDomain\User\UserId;

class YamlUserRepositoryTest extends TestCase
{
    private $cacheDir;

    private $repository;

    protected function setUp()
    {
        $this->cacheDir   = vfsStream::setup('cache');
        $this->repository = new YamlUserRepository(vfsStream::url('cache/users.yml'));
    }

    protected function addUsers()
    {
        $this->repository->add(
            new User(new UserId('62A0CEB4-0403-4AA6-A6CD-1EE808AD4D23'), 'admin@task.reader', 'admin')
        );
        $this->repository->add(
            new User(new UserId('8CE05088-ED1F-43E9-A415-3B3792655A9B'), 'user@task.reader', 'user')
        );
    }

    public function testFind()
    {
        $this->addUsers();

        $user = $this->repository->find(
            new UserId('62A0CEB4-0403-4AA6-A6CD-1EE808AD4D23')
        );

        $this->assertNotNull($user);
        $this->assertInstanceOf('TaskReader\CoreDomain\User\User', $user);
        $this->assertEquals('admin@task.reader', $user->getEmail());
    }

    public function testFindReturnsNullIfNotFound()
    {
        $user = $this->repository->find(
            new UserId('62A0CEB4-0403-4AA6-A6CD-1EE808AD4D23')
        );

        $this->assertNull($user);
    }

    public function testAdd()
    {
        $this->addUsers();
        $expected = <<<YAML
-
    id: 62A0CEB4-0403-4AA6-A6CD-1EE808AD4D23
    email: admin@task.reader
    password: admin
-
    id: 8CE05088-ED1F-43E9-A415-3B3792655A9B
    email: user@task.reader
    password: user

YAML;

        $this->assertEquals(
            $expected,
            $this->cacheDir->getChild('users.yml')->getContent()
        );
    }
}
