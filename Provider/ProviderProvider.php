<?php

namespace DoS\SMSBundle\Provider;

use Doctrine\ORM\EntityManager;
use DoS\SMSBundle\Model\ProviderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class ProviderProvider
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var string
     */
    protected $defaultProvider;

    public function __construct(EntityManager $manager, $dataClass, $defaultProvider = 'dummy')
    {
        $this->manager = $manager;
        $this->dataClass = $dataClass;
        $this->repository = $manager->getRepository($dataClass);
        $this->defaultProvider = $defaultProvider;
    }

    /**
     * @param $name
     */
    public function setDefaultProvider($name)
    {
        $this->defaultProvider = $name;
    }

    /**
     * @param $name
     *
     * @return null|ProviderInterface
     */
    public function findByName($name)
    {
        return $this->repository->findOneBy(array('name' => $name));
    }

    /**
     * @return null|ProviderInterface
     */
    public function getActivedProvider()
    {
        if ($provider = $this->repository->findOneBy(array('actived' => true))) {
            return $provider;
        }

        return $this->findByName($this->defaultProvider);
    }

    /**
     * Get paramter by provider name.
     *
     * @param $name
     *
     * @return array
     */
    public function getParameters($name)
    {
        if (!$provider = $this->findByName($name)) {
            return $provider->getParameters();
        }

        return array();
    }
}
