<?php declare(strict_types=1);

namespace App\Repositories\User;

use App\Core\Database;
use App\Core\Session;
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
        try {

            $users = $this->builder
                ->select('*')
                ->from('users')
                ->fetchAllAssociative();

            $userCollection = [];
            foreach ($users as $user) {
                $userCollection[] = $this->buildModel((object)$user);
            }
            return $userCollection;

        } catch (\Exception $e) {
            return [];
        }
    }

    public function getById(int $userId): ?User
    {
        try {
            $user = $this->builder
                ->select('*')
                ->from('users')
                ->where('id = :id')
                ->setParameter('id', $userId)
                ->fetchAssociative();

        } catch (\Exception $e) {
            return null;
        }

        if (!$user) {
            return null;
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
            ])
            ->setParameter('email', $user->getEmail())
            ->setParameter('password', $password)
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
            ->setParameter('email', $user->getEmail())
            ->executeStatement();

       if($user > 0){
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

        if(!$user || !password_verify($password, $user['password'])){
            return null;
        }
        return $this->buildModel((object) $user);
    }

    private function buildModel(\stdClass $user): User
    {
        return new User(
            $user->email,
            $user->password,
            $user->name,
            $user->username,
            (int)$user->id,
        );
    }
}