<?php

namespace DoS\SMSBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecordControllerTest extends WebTestCase
{
    public function testSend()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sms/send');

        $this->assertTrue($crawler->filter('html:contains("Hello World")')->count() > 0);
    }
}
