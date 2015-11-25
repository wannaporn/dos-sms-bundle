<?php

namespace DoS\SMSBundle\Provider;

use Doctrine\ORM\EntityManager;
use DoS\ResourceBundle\Doctrine\ORM\EntityRepository;
use DoS\ResourceBundle\Factory\ResourceFactoryAware;
use DoS\SMSBundle\Model\RecordInterface;
use DoS\SMSBundle\SMS\ProviderInterface;
use libphonenumber\PhoneNumberUtil;
use SmsSender\Result\ResultInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class RecordProvider extends ResourceFactoryAware
{
    /**
     * @var ProviderProvider
     */
    protected $provider;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $dataClass;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ProviderProvider $provider,
        EntityManager $manager,
        $dataClass
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->provider = $provider;
        $this->manager = $manager;
        $this->dataClass = $dataClass;
        $this->repository = $manager->getRepository($dataClass);
    }

    /**
     * @return ProviderProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param $name
     *
     * @return array
     */
    public function getProviderParameters($name)
    {
        return $this->provider->getParameters($name);
    }

    /**
     * @return RecordInterface
     */
    public function createNew()
    {
        return $this->factory->createNew();
    }

    /**
     * @param $transactionId
     *
     * @return null|RecordInterface
     */
    public function findTransactionId($transactionId)
    {
        return $this->repository->findOneBy(
            array(
                'transactionId' => $transactionId,
            )
        );
    }

    /**
     * @param ResultInterface $result
     *
     * @return RecordInterface
     */
    public function storeResult(ResultInterface $result)
    {
        $number = PhoneNumberUtil::getInstance()->parse($result->getRecipient(), 'TH');
        $provider = $this->provider->getActivedProvider();
        $object = $this->createNew();

        $object->setMessage($result->getBody());
        $object->setTransactionId($result->getId());
        $object->setNumber($number);
        $object->setState($object::STATE_SENT);
        $object->setPrice($provider->getPrice());
        $object->setProvider($provider);
        $object->setCurrency($provider->getCurrency());

        $event = new GenericEvent($object);

        $this->dispatchEvent('dos_sms_record_pre_store', $event);

        $this->manager->persist($object);
        $this->manager->flush();

        $this->dispatchEvent('dos_sms_record_post_store', $event);

        return $object;
    }

    /**
     * @param string $name
     * @param Event  $event
     *
     * @return Event
     */
    public function dispatchEvent($name, Event $event)
    {
        return $this->eventDispatcher->dispatch($name, $event);
    }

    /**
     * @param ProviderInterface $provider
     */
    public function visit(ProviderInterface $provider)
    {
        foreach ($provider->getCallbackResults() as $result) {
            if ($record = $this->findTransactionId($result->getMessageId())) {
                $record->responded($result->getData(), $result->isSuccess());
                $record->setPrice($result->getPrice());

                $event = new GenericEvent($record);

                $this->dispatchEvent('dos_sms_record_pre_response', $event);

                $this->manager->persist($record);
                $this->manager->flush();

                $this->dispatchEvent('dos_sms_record_post_response', $event);
            }
        }
    }
}
