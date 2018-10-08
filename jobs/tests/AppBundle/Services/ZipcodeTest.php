<?php

namespace Tests\AppBundle\Services;

use AppBundle\Entity\Zipcode as ZipcodeEntity;
use AppBundle\Repository\ZipcodeRepository;
use AppBundle\Services\Zipcode;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Tests\Functional\WebTestCase;

class ZipcodeTest extends WebTestCase
{
    /**
     * @var ZipcodeRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ZipcodeEntity
     */
    private $defaultEntity;

    public function setUp()
    {
        $this->repository = $this->getMockBuilder(ZipcodeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll', 'find'])
            ->getMock();

        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->defaultEntity = new ZipcodeEntity('01623', 'Lommatzsch');
    }

    public function testFindWhenServiceIsFoundReturnsService()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($this->defaultEntity))
            ->with('01623');

        $zipcode = new Zipcode($this->repository, $this->entityManager);
        $this->assertEquals($this->defaultEntity, $zipcode->find('01623'));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage city: The city must have at least 5 characters
     */
    public function testCreateZipcodeWithInvalidCityThrowsBadRequestHttpException()
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

        $zipcode = new Zipcode($this->repository, $this->entityManager);
        $zipcode->create(new ZipcodeEntity('12345', 'ab'));
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedExceptionMessage id: This value should have exactly 5 characters.
     */
    public function testCreateZipcodeWithInvalidIdThrowsBadRequestHttpException()
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

        $zipcode = new Zipcode($this->repository, $this->entityManager);
        $zipcode->create(new ZipcodeEntity('123456', 'city'));
    }

    public function testCreateWithValidZipcodeReturnsPersistedZipcode()
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue(null))
            ->with('01623');
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->defaultEntity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $zipcode = new Zipcode($this->repository, $this->entityManager);
        $zipcode->create($this->defaultEntity);
    }
}
