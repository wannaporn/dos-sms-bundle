<?php

namespace DoS\SMSBundle\SMS\Provider;

use DoS\SMSBundle\SMS\ProviderCallbackInterface;
use DoS\SMSBundle\SMS\ResultCallback;

class NexmoCallback implements ProviderCallbackInterface
{
    /**
     * @var ResultCallback[]
     */
    protected $results;

    /**
     * @param array $data
     *
     * @return array
     */
    protected function cleanKeys(array $data)
    {
        $return = array();

        foreach ($data as $key => $value) {
            $return[strtolower(preg_replace('/\W/', '', $key))] = $value;
        }

        return $return;
    }

    protected function processResponse(array $data)
    {
        $result = new ResultCallback($data);
        $result->setMessageId($data['messageid']);
        $result->setPrice($data['price'] * 100);
        $result->setSuccess(empty($data['errorcode']));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function process(array $response)
    {
        $response = $this->cleanKeys($response);

        if (array_key_exists('messages', $response)) {
            foreach ($response['messages'] as $message) {
                $this->results[] = $this->processResponse($message);
            }
        } else {
            $this->results[] = $this->processResponse($response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * {@inheritdoc}
     */
    public function getReponseCodes()
    {
        return array(
            array(
                'code' => 0,
                'message' => 'Success',
                'description' => 'The message was successfully accepted for delivery by Nexmo',
            ),
            array(
                'code' => 1,
                'message' => 'Throttled',
                'description' => 'You have exceeded the submission capacity allowed on this account, please back-off and retry',
            ),
            array(
                'code' => 2,
                'message' => 'Missing params',
                'description' => 'Your request is incomplete and missing some mandatory parameters',
            ),
            array(
                'code' => 3,
                'message' => 'Invalid params',
                'description' => 'The value of one or more parameters is invalid',
            ),
            array(
                'code' => 4,
                'message' => 'Invalid credentials',
                'description' => 'The api_key / api_secret you supplied is either invalid or disabled',
            ),
            array(
                'code' => 5,
                'message' => 'Internal error',
                'description' => 'An error has occurred in the Nexmo platform whilst processing this message',
            ),
            array(
                'code' => 6,
                'message' => 'Invalid message',
                'description' => 'The Nexmo platform was unable to process this message, for example, an un-recognized number prefix or the number is not whitelisted if your account is new',
            ),
            array(
                'code' => 7,
                'message' => 'Number barred',
                'description' => 'The number you are trying to submit to is blacklisted and may not receive messages',
            ),
            array(
                'code' => 8,
                'message' => 'Partner account barred',
                'description' => 'The api_key you supplied is for an account that has been barred from submitting messages',
            ),
            array(
                'code' => 9,
                'message' => 'Partner quota exceeded',
                'description' => 'Your pre-pay account does not have sufficient credit to process this message',
            ),
            array(
                'code' => 11,
                'message' => 'Account not enabled for REST',
                'description' => 'This account is not provisioned for REST submission, you should use SMPP instead',
            ),
            array(
                'code' => 12,
                'message' => 'Message too long',
                'description' => 'Applies to Binary submissions, where the length of the UDH and the message body combined exceed 140 octets',
            ),
            array(
                'code' => 13,
                'message' => 'Communication Failed',
                'description' => 'Message was not submitted because there was a communication failure',
            ),
            array(
                'code' => 14,
                'message' => 'Invalid Signature',
                'description' => 'Message was not submitted due to a verification failure in the submitted signature',
            ),
            array(
                'code' => 15,
                'message' => 'Invalid sender address',
                'description' => 'The sender address (from parameter) was not allowed for this message. Restrictions may apply depending on the destination see https://help.nexmo.com/hc/en-us/sections/200622473-Country-Specific-Features-and-Restrictions',
            ),
            array('code' => 16, 'message' => 'Invalid TTL', 'description' => 'The ttl parameter values is invalid'),
            array(
                'code' => 19,
                'message' => 'Facility not allowed',
                'description' => 'Your request makes use of a facility that is not enabled on your account',
            ),
            array(
                'code' => 20,
                'message' => 'Invalid Message class',
                'description' => 'The message class value supplied was out of range (0 - 3)',
            ),
        );
    }
}
