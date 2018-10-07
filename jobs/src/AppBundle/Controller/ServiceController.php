<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Service as ServiceEntity;
use AppBundle\Form\ServiceType;
use AppBundle\Services\Service;
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
    public function getAllAction(): View
    {
        return new View(
            $this->container->get(Service::class)->findAll(),
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
    public function getAction(int $id): View
    {
        $service = $this->container->get(Service::class)->find($id);
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
    public function postAction(Request $request): View
    {
        $parameters = $request->request->all();
        $attributes = $this->getAttributes($parameters);
        /** @todo: Implement a builder */
        $service = new ServiceEntity($attributes['id'], $attributes['name']);

        $persistedService = $this->container->get(Service::class)->create($service);

        return new View(
            $persistedService,
            Response::HTTP_CREATED
        );
    }

    /**
     * @param array $parameters
     * @return array
     */
    private function getAttributes(array $parameters): array
    {
        $attributes = [];
        $attributes['id'] = isset($parameters['id']) ? $parameters['id'] : null;
        $attributes['name'] = isset($parameters['name']) ? $parameters['name'] : null;

        return $attributes;
    }
}
