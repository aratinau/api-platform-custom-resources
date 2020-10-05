<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

// implement DataPersisterInterface
class UserDataPersister implements ContextAwareDataPersisterInterface
{
    /*
     * L'interface ContextAwareDataPersisterInterface
     * permet d'avoir le contexte dans les méthodes
     */

    private $decoratedDataPersister;
    private $userPasswordEncoder;
    private $logger;
    private $security;

    public function __construct(DataPersisterInterface $decoratedDataPersister, UserPasswordEncoderInterface $userPasswordEncoder, LoggerInterface $logger, Security $security)
    {
        $this->decoratedDataPersister = $decoratedDataPersister;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->logger = $logger;
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
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

    /*
     * Data persisters are the way to do logic before or after something saves.
     * But what if you want to do something only when your object is being created?
     * Or only when it's being updated? Or maybe only when a specific field changes from one value to another?
     *
     * Here's our first goal: log a message only when a user is created in our API.
     * And this one is pretty simple. Start by adding a third argument to the constructor for LoggerInterface $logger.
     * I'll hit Alt+Enter and go to "Initialize Properties" as a shortcut to create that property and set it:
     */

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        if (($context['item_operation_name'] ?? null) === 'put') {
            $this->logger->info(sprintf('User "%s" is being updated!', $data->getId()));
        }

        // à la création du User:
        if (!$data->getId()) {
            // take any actions needed for a new user
            // send registration email
            // integrate into some CRM or payment system
            $this->logger->info(sprintf('User %s just registered! Eureka!', $data->getEmail()));
        }

        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        // now handled in a listener
        // $data->setIsMe($this->security->getUser() === $data);

        return $this->decoratedDataPersister->persist($data);
    }

    public function remove($data, array $context = [])
    {
        $this->decoratedDataPersister->remove($data);
    }
}
