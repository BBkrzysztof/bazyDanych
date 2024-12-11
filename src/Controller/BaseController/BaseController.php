<?php

namespace App\Controller\BaseController;

use App\Serializer\EntitySerializer;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Security\Service\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected EntitySerializer $entitySerializer;
    protected Validator $validator;
    protected EntityManagerInterface $entityManager;
    protected Security $security;

    public function __construct(
        EntitySerializer       $entitySerializer,
        Validator              $validator,
        EntityManagerInterface $entityManager,
        Security               $security
    )
    {
        $this->entitySerializer = $entitySerializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->security = $security;
    }
}