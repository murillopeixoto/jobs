<?php

namespace AppBundle\Persister;

use AppBundle\Entity\EntityInterface;

interface PersisterInterface
{
    public function save(EntityInterface $entity): EntityInterface;
}
