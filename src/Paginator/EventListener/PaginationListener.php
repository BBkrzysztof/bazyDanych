<?php

namespace App\Paginator\EventListener;

use App\Paginator\Annotation\Pagination;
use App\Paginator\Paginator;
use Doctrine\Common\Annotations\Reader;
use Security\Service\Security;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class PaginationListener
{

    private Reader $annotationReader;
    private Security $security;
    private Paginator $paginator;


    public function __construct(
        Reader    $annotationReader,
        Security  $security,
        Paginator $paginator
    )
    {
        $this->annotationReader = $annotationReader;
        $this->security = $security;
        $this->paginator = $paginator;
    }

    /**
     * @throws \ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if ($controller instanceof ErrorController) {
            return;
        }

        [$controller, $method] = $controller;

        $annotations = $this->annotationReader->getMethodAnnotations(new \ReflectionMethod($controller, $method));

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Pagination) {
                $request = $event->getRequest();
                $currentPage = (int)$request->query->get('page') ?: 1;
                $limit = (int)$request->query->get('limit') ?: 25;
                $this->paginator->setPagination($currentPage, $limit);

                $likeFilters = [];
                $eqFilters = [];

                foreach ($annotation->likeFilters as $filter) {
                    if (!$request->query->has($filter)) continue;
                    $likeFilters[$filter] = $request->query->get($filter);
                }

                foreach ($annotation->eqFilters as $filter) {
                    if (!$request->query->has($filter)) continue;
                    $eqFilters[$filter] = $request->query->get($filter);
                }

                $this->paginator->setLikeFilters($likeFilters);
                $this->paginator->setEqFilters($eqFilters);
            }
        }
    }

}