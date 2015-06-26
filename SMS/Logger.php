<?php

namespace DoS\SMSBundle\SMS;

use Psr\Log\LoggerInterface;
use SmsSender\Exception\WrappedException;
use SmsSender\Result\ResultInterface;

class Logger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $smsList = array();

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Log a SMS.
     *
     * @param Resultinterface $sms            The SMS to log.
     * @param float           $duration       The time required to send the SMS (in seconds).
     * @param string          $provider_class The class name of the provider which sent the SMS.
     */
    public function logMessage(ResultInterface $sms, $duration, $provider_class)
    {
        $this->smsList[] = array(
            'sms' => $sms,
            'duration' => $duration,
            'provider_class' => $provider_class,
        );

        if ($this->logger !== null) {
            $message = sprintf(
                'SMS sent to %s, from %s %0.2f ms (%s), status: %s',
                $sms->getRecipient(),
                $sms->getOriginator(),
                $duration * 1000,
                $provider_class,
                $sms->getStatus()
            );
            $this->logger->info($message);
        }
    }

    /**
     * @param WrappedException $error
     * @param $provider_class
     */
    public function logError(WrappedException $error, $provider_class)
    {
        if ($this->logger === null) {
            return;
        }

        $realError = $error->getWrappedException();
        $sms = $error->getSms();
        $message = sprintf(
            'Failed to sent SMS to %s, from %s. Error message: "%s", code %d (%s)',
            $sms['recipient'],
            $sms['originator'],
            $realError->getMessage(),
            $realError->getCode(),
            $provider_class
        );

        $this->logger->error($message);
    }

    /**
     * @return array
     */
    public function getSms()
    {
        return $this->smsList;
    }
}
