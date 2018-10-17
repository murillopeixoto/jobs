<?php

namespace AppBundle\Services;

use AppBundle\Persister\PersisterInterface;
use AppBundle\Repository\ZipcodeRepository;
use Doctrine\ORM\EntityManagerInterface;

class Zipcode extends AbstractService
{
    /**
     * Service constructor.
     * @param ZipcodeRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ZipcodeRepository $repository,
        PersisterInterface $persister
    ) {
        $this->repository = $repository;
        $this->persister = $persister;
    }
}
