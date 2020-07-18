<?php


namespace App\Calendar\Infrastructure\Repository;


use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Model\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    private UuidProviderInterface $uuidProvider;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->uuidProvider = $uuidProvider;
        $this->entityManager = $entityManager;
    }

    public function createUser(User $user): void
    {
        $dbUser = new \App\Entity\User();
        $dbUser->setName($user->getName());
        $dbUser->setLogin($user->getLogin());
        $dbUser->setSurname($user->getSurname());
        $dbUser->setPatronymic($user->getPatronymic());
        $dbUser->setUuid($this->uuidProvider->stringToBytes($user->getUuid()));

        $this->entityManager->persist($dbUser);
        $this->entityManager->flush();
    }

    public function isUserExistByLogin(string $login): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\User::class);
        return $repository->findOneBy(array('login' => $login)) !== null;
    }

    public function isUserExistById(string $uuid): bool
    {
        $repository = $this->entityManager->getRepository(\App\Entity\User::class);
        return $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($uuid))) !== null;
    }

    public function deleteUserById(string $uuid): void
    {
        $repository = $this->entityManager->getRepository(\App\Entity\User::class);
        $record = $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($uuid)));

        $this->entityManager->remove($record);
        $this->entityManager->flush();
    }
}