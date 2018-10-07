<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Zipcode as ZipcodeEntity;
use AppBundle\Services\Zipcode;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class ZipcodeController extends FOSRestController
{
    /**
     * @Rest\Get("/zipcode")
     * @return View
     */
    public function getAllAction(): View
    {
        return new View(
            $this->container->get(Zipcode::class)->findAll(),
            Response::HTTP_OK
        );
    }

    /**
     * @Rest\Get("/zipcode/{id}")
     *
     * @param String id
     * @throws NotFoundHttpException
     * @return View
     */
    public function getAction(String $id): View
    {
        $service = $this->container->get(Zipcode::class)->find($id);
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
     * @Rest\Post("/zipcode")
     */
    public function postAction(Request $request): View
    {
        $parameters = $request->request->all();
        $attributes = $this->getAttributes($parameters);
        /** @todo: Implement a builder */
        $service = new ZipcodeEntity($attributes['id'], $attributes['city']);

        $persistedService = $this->container->get(Zipcode::class)->create($service);

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
        $attributes['city'] = isset($parameters['city']) ? $parameters['city'] : null;

        return $attributes;
    }
}
