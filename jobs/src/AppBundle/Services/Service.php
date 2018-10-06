<?php

namespace AppBundle\Services;

use AppBundle\Repository\ServiceRepository;
use AppBundle\Entity\Service as EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class Service
{
    /**
     * @var ServiceRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Service constructor.
     * @param ServiceRepository $repository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ServiceRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param int $id
     * @return EntityService
     */
    public function find(int $id): ?EntityService
    {
        try {
            $service = $this->repository->find($id);
        } catch (\Exception $exception) {
            $service = null;
        }

        return $service;
    }

    /**
     * @param EntityService $entity
     * @throws BadRequestHttpException
     * @return EntityService
     */
    public function create(EntityService $entity): EntityService
    {
        /** @var RecursiveValidator $validator */
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($entity);
        if (count($errors)) {
            /** @todo Create an errorMessageHandler */
            $errorMessages = [];
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorMessages[] = sprintf(
                    '%s: %s',
                    $error->getPropertyPath(),
                    $error->getMessage()
                );
            }

            throw new BadRequestHttpException(implode($errorMessages, ', '));
        }

        if ($this->find($entity->getId())) {
            throw new BadRequestHttpException(sprintf('Service \'%s\' already exists', $entity->getId()));
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}
