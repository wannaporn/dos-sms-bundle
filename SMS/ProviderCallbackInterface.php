<?php

namespace DoS\SMSBundle\SMS;

interface ProviderCallbackInterface
{
    /**
     * @param array $response
     */
    public function process(array $response);

    /**
     * @return array
     */
    public function getReponseCodes();

    /**
     * @return ResultCallback[]
     */
    public function getResults();
}
