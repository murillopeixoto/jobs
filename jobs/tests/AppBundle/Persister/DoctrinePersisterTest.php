<?php

namespace Tests\AppBundle\Persister;

use AppBundle\Entity\Job as JobEntity;
use AppBundle\Persister\DoctrinePersister;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Tests\Functional\WebTestCase;
use DateTime;

/**
 * @group unit
 */
class DoctrinePersisterTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testSaveEntityWithoutIdShouldUsePersist()
    {
        $entity = new JobEntity(
            802031,
            '01621',
            'Job to be done',
            'description',
            new DateTime('2018-11-11')
        );

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($entity);
        $this->entityManager
            ->expects($this->never())
            ->method('merge');
        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->will($this->returnValue($entity));

        $persister = new DoctrinePersister($this->entityManager);
        $persister->save($entity);
    }

    public function testSaveEntityWithIdShouldUseMerge()
    {
        $entity = new JobEntity(
            802031,
            '01621',
            'Job to be done',
            'description',
            new DateTime('2018-11-11'),
            'a1c59e8f-ca88-11e8-94bd-0242ac130005'
        );

        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->once())
            ->method('merge')
            ->with($entity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->will($this->returnValue($entity));

        $persister = new DoctrinePersister($this->entityManager);
        $persister->save($entity);
    }
}
