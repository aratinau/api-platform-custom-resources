<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{
    private $decoratedDataPersister;
    private $userPasswordEncoder;

    public function __construct(DataPersisterInterface $decoratedDataPersister, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->decoratedDataPersister = $decoratedDataPersister;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /*
     * note
     * Ce qu'on cherche à injecter dans notre DataPersister
     * ApiPlatform\Core\Bridge\Doctrine\Common\DataPersister
     *
     * php bin/console debug:container api_platform.serializer.context_builder --show-arguments
     *
     */

    /**
     * @param User $data
     */
    public function persist($data)
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }
        return $this->decoratedDataPersister->persist($data);
    }

    public function remove($data)
    {
        $this->decoratedDataPersister->remove($data);
    }
}
