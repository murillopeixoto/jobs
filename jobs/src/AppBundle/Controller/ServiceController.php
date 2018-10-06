<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Service;
use AppBundle\Form\ServiceType;
use AppBundle\Services\Service as ServiceService;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class ServiceController extends FOSRestController
{
    /**
     * @Rest\Get("/service")
     * @return View
     */
    public function getAction(): View
    {
        return new View(
            $this->container->get(ServiceService::class)->findAll(),
            Response::HTTP_OK
        );
    }

    /**
     * @Rest\Get("/service/{id}")
     *
     * @param int id
     * @throws NotFoundHttpException
     * @return View
     */
    public function getServiceAction(int $id): View
    {
        $service = $this->container->get(ServiceService::class)->find($id);
        if (!$service) {
            throw new NotFoundHttpException(sprintf(
                'The resource \'%s\' was not found.',
                $id
            ));
        }

        return new View(
            $service,
            Response::HTTP_OK
        );
    }

    /**
     * @Rest\Post("/service")
     */
    public function postServiceAction(Request $request): View
    {
        $parameter = $request->request->all();
        /** @todo: Implement a builder */
        $service = new Service();
        if (isset($parameter['id'])) {
            $service->setId($parameter['id']);
        }
        if (isset($parameter['name'])) {
            $service->setName($parameter['name']);
        }

        $persistedService = $this->container->get(ServiceService::class)->create($service);

        return new View(
            $persistedService,
            Response::HTTP_CREATED
        );
    }
}
