<?php


namespace App\Calendar\Infrastructure\Repository;


use App\Calendar\App\Uuid\UuidProviderInterface;
use App\Calendar\Domain\Model\User;
use App\Calendar\Domain\Model\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository implements UserRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private UuidProviderInterface $uuidProvider;

    public function __construct(EntityManagerInterface $entityManager, UuidProviderInterface $uuidProvider)
    {
        $this->entityManager = $entityManager;
        $this->uuidProvider = $uuidProvider;
    }

    public function createUser(User $user): void
    {
        $dbUser = new \App\Entity\User();
        $dbUser->setLogin($user->getLogin());
        $dbUser->setName($user->getName());
        $dbUser->setPatronymic($user->getPatronymic());
        $dbUser->setSurname($user->getSurname());
        $dbUser->setUuid($this->uuidProvider->stringToBytes($user->getUuid()));

        $this->entityManager->persist($dbUser);
        $this->entityManager->flush();
    }

    public function isUserExistByLogin(string $login): bool
    {
        $repository = $this->entityManager->getRepository('App\Entity\User');
        return $repository->findOneBy(array('login' => $login)) !== null;
    }

    public function isUserExistById(string $uuid): bool
    {
        $repository = $this->entityManager->getRepository('App\Entity\User');
        return $repository->findOneBy(array('uuid' => $this->uuidProvider->stringToBytes($uuid))) !== null;
    }
}