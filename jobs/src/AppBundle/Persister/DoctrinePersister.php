<?php

namespace AppBundle\Persister;

use AppBundle\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrinePersister implements PersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(EntityInterface $entity): EntityInterface
    {
        if (is_null($entity->getId())) {
            $this->entityManager->persist($entity);
        } else {
            $this->entityManager->merge($entity);
        }

        $this->entityManager->flush();

        return $entity;
    }
}
