<?php

namespace Tests\AppBundle\Services;

use AppBundle\Entity\Service as ServiceEntity;
use AppBundle\Repository\ServiceRepository;
use AppBundle\Services\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ServicesTest extends WebTestCase
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
     * @var ServiceEntity
     */
    private $defaultEntity;

    public function setUp()
    {
        $this->repository = $this->getMockBuilder(ServiceRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll', 'find'])
            ->getMock();

        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->defaultEntity = new ServiceEntity();
        $this->defaultEntity->setId(1)->setName('service');
    }

    public function testFindAllWithoutValueReturnsEmptyArray()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([]));

        $service = new Service($this->repository, $this->entityManager);
        $this->assertEmpty($service->findAll());
    }

    public function testFindAllWithServicesFoundReturnsArrayWithServices()
    {
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([$this->defaultEntity]));

        $service = new Service($this->repository, $this->entityManager);
        $this->assertEquals([$this->defaultEntity], $service->findAll());
    }

    public function testFindWhenServiceIsNotFoundReturnsNull()
    {
        $service = new Service($this->repository, $this->entityManager);
        $this->assertNull($service->find(1));
    }

    public function testFindWhenServiceIsFoundReturnsService()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultEntity))
            ->with(1);

        $service = new Service($this->repository, $this->entityManager);
        $this->assertEquals($this->defaultEntity, $service->find(1));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage id: This value should not be blank., name: This value should not be blank.
     */
    public function testCreateWithInvalidServiceThrowsBadRequestHttpException()
    {
        $this->repository
            ->expects($this->never())
            ->method('find');
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new Service($this->repository, $this->entityManager);
        $service->create(new ServiceEntity());
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage Service '1' already exists
     */
    public function testCreateWithDuplicatedServiceThrowsBadRequestHttpException()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultEntity))
            ->with(1);
        $this->entityManager
            ->expects($this->never())
            ->method('persist');
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $service = new Service($this->repository, $this->entityManager);
        $service->create($this->defaultEntity);
    }

    public function testCreateWithValidServiceReturnsPersistedService()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with(1);
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->defaultEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $service = new Service($this->repository, $this->entityManager);
        $service->create($this->defaultEntity);
    }
}
