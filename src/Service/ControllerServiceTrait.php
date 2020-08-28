<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Common\Persistence\ObjectRepository;

trait ControllerServiceTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ParameterBagInterface
     */
    protected $params;

    /**
     * @param EntityManagerInterface $em
     */
    protected function setEm(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @see https://symfony.com/doc/current/service_container.html#getting-container-parameters-as-a-service
     * @param ParameterBagInterface $params
     */
    protected function setParameterBag(ParameterBagInterface $params): void
    {
        $this->params = $params;
    }

    /**
     * It returns config parameter
     *
     * @param string $name
     * @return mixed
     */
    protected function configParameter(string $name)
    {
        return $this->params->get($name);
    }

    /**
     * It returns path to project dir
     *
     * @return string
     */
    protected function getProjectDirPath(): string
    {
        return $this->configParameter('kernel.project_dir');
    }

    /**
     * It returns path to public dir
     *
     * @return string
     */
    protected function getPublicDirPath(): string
    {
        return $this->getProjectDirPath() . '/public';
    }

    /**
     * @param string $entityName
     * @return ObjectRepository
     */
    protected function repository(string $entityName): ObjectRepository
    {
        return $this->em->getRepository($entityName);
    }
}