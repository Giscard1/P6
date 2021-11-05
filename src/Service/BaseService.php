<?php


namespace App\Service;


use Psr\Container\ContainerInterface;

class BaseService
{
    public $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    function persist($entity)
    {
        $this->getEm()->persist($entity);
    }

    function getEm()
    {
        return $this->getDoctrine()->getManager();
    }

    function flush()
    {
        $this->getEm()->flush();
    }
}
