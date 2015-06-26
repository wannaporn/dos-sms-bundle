<?php

namespace DoS\SMSBundle\Controller;

use DoS\ResourceBundle\Controller\ResourceController;
use DoS\SMSBundle\Provider\RecordProvider;
use DoS\SMSBundle\SMS\StorableSender;
use SmsSender\Exception\WrappedException;
use Symfony\Component\HttpFoundation\Request;

class RecordController extends ResourceController
{
    /**
     * @return RecordProvider
     */
    protected function getRecordProvider()
    {
        return $this->get('dos.sms.provider.record');
    }

    /**
     * @return StorableSender
     */
    protected function getSmsSender()
    {
        return $this->get('dos.sms.sender');
    }

    public function sendAction(Request $request)
    {
        return $this->handleView($this->view()->setTemplate($this->config->getTemplate('send.html')));
    }

    public function callbackAction(Request $request, $provider)
    {
        sleep(5);
        $data = array();
        $status = 200;

        try {
            $this->getSmsSender()->acceptCallback($provider, $request->query->all());
        } catch (WrappedException $e) {
            $data = array('error' => $e->getWrappedException()->getMessage());
            $status = 400;
        } catch (\Exception $e) {
            $data = array('error' => $e->getMessage());
            $status = 400;
        }

        return $this->handleView($this->view($data, $status));
    }
}
