<?php

namespace DoS\SMSBundle\Model;

trait SoftDeleteTrait
{
    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     *{@inheritdoc}
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     *{@inheritdoc}
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function isDeleted()
    {
        return null !== $this->deletedAt && new \DateTime() >= $this->deletedAt;
    }
}
