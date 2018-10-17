<?php

namespace Tests\AppBundle\Services;

use AppBundle\Persister\PersisterInterface;
use FOS\RestBundle\Tests\Functional\WebTestCase;

abstract class AbstractServicesTest extends WebTestCase
{
    /**
     * @var PersisterInterface
     */
    protected $persister;

    public function setUp()
    {
        $this->persister = $this->getMockBuilder(PersisterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
