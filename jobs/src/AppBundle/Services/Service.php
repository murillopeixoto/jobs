<?php

namespace AppBundle\Services;

use AppBundle\Persister\PersisterInterface;
use AppBundle\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;

class Service extends AbstractService
{
    /**
     * Service constructor.
     * @param ServiceRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ServiceRepository $repository,
        PersisterInterface $persister
    ) {
        $this->repository = $repository;
        $this->persister = $persister;
    }
}
