<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Json\Server\Response as JsonResponse;
use Zend\Json\Server\Server as JsonServer;
use Zend\Json\Server\Request;

class GatewayController extends AbstractActionController
{
    protected $map = [
        'push.device' => 'Push\Model\Device'
    ];

    /**
     * URL： /
     */
    public function dispatchAction()
    {
        try {
            $application = $this->getEvent()->getApplication();
            $services    = $application->getServiceManager();

            // $config = $services->get('Config');
            // $models = empty($config['models'])?[]: $config['models'];

            $server = new JsonServer();
            foreach ($this->map as $k => $v) {
                $model = $this->model($v);
                $server->setClass($model, $k);
            }
            $response = new JsonResponse();
            $response->setVersion('2.0');
            $server->setResponse($response);
            $server->setReturnResponse();

            $res = $server->handle();
            echo ($res->getServiceMap()->__toString());
            exit();
            $this->server = $server;

            $request = $this->getRequest();
            $content = $request->getContent();

            $jsons  = json_decode($content, true);
            $return = [];
            foreach ($jsons as $json) {
                $response  = $this->process($json);
                $return[] = $response->getResult();
            }
            if (count($jsons) == 1) {
                $return = current($return);
            }
        }catch(\Exception $e) {
            //var_dump($e->getMessage());
            $return = json_decode('{"jsonrpc": "2.0", "error": {"code": -32603, "message": "Internal error"}, "id": null}', true);
        }

        return new JsonModel($return);
    }

    protected function process($request)
    {
        $server = clone $this->server;

        if (($request instanceof Request)) {
            throw new \InvalidArgumentException('Invalid request provided');
            // throw new Exception\InvalidArgumentException('Invalid request provided');
        }

        $server->setRequest($request);

        $response = $server->handle();

        return $response;
    }
}
