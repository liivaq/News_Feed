<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Core\Database;
use App\Exceptions\RecourseNotFoundException;
use App\Models\User;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class DatabaseUserRepository implements UserRepository
{
    private QueryBuilder $builder;
    private Connection $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
        $this->builder = $this->connection->createQueryBuilder();
    }

    public function all(): array
    {
        $users = $this->builder
            ->select('*')
            ->from('users')
            ->fetchAllAssociative();

        $userCollection = [];

        if ($users) {
            foreach ($users as $user) {
                $userCollection[] = $this->buildModel((object)$user);
            }
        }

        return $userCollection;
    }


    public
    function getById(int $userId): ?User
    {
        $user = $this->builder
            ->select('*')
            ->from('users')
            ->where('id = :id')
            ->setParameter('id', $userId)
            ->fetchAssociative();

        if (!$user) {
            throw new RecourseNotFoundException('User by id ' . $userId . ' was not found');
        }

        return $this->buildModel((object)$user);
    }

    public function store(User $user): User
    {
        $password = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $this->builder
            ->insert('users')
            ->values([
                'email' => ':email',
                'password' => ':password',
                'username' => ':username',
                'avatar_url' => ':avatar'
            ])
            ->setParameter('email', $user->getEmail())
            ->setParameter('password', $password)
            ->setParameter('username', $user->getUsername())
            ->setParameter('avatar', $user->getAvatarUrl())
            ->executeStatement();

        $user->setId((int)$this->connection->lastInsertId());
        return $user;
    }

    public function authenticate(User $user): bool
    {
        $user = $this->builder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->orWhere('username = :username')
            ->setParameter('email', $user->getEmail())
            ->setParameter('username', $user->getUsername())
            ->executeStatement();

        if ($user > 0) {
            return true;
        }
        return false;
    }

    public function login(string $email, string $password): ?User
    {
        $user = $this->builder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)->fetchAssociative();

        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        return $this->buildModel((object)$user);
    }

    public function update(User $user): void
    {
        $this->builder
            ->update('users')
            ->set('email', ':email')
            ->set('username', ':username')
            ->where('id = :id')
            ->setParameter('username', $user->getUsername())
            ->setParameter('email', $user->getEmail())
            ->executeStatement();
    }

    private function buildModel(\stdClass $user): User
    {
        return new User(
            $user->email,
            $user->password,
            $user->username,
            $user->avatar_url,
            (int)$user->id,
        );
    }
}