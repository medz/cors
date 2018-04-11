<?php

declare(strict_types=1);

namespace Medz\Cors\ThinkPHP\Hook;

use Medz\Cors\CorsInterface;
use think\Facade\Request as RequestFacade;
use think\Request;
use think\Response;

class ResponseSend
{
    protected $cors;
    protected $request;

    public function __construct(CorsInterface $cors)
    {
        RequestFacade::hook('getRequestInstance', function (Request $request) {
            return $request;
        });

        $this->request = $this->getRequestInstance();
        $this->cors = $cors;
    }

    public function handle(Response $response)
    {
        $this->cors->setRequest('thinkphp', $this->request);
        $this->cors->setResponse('thinkphp', $response);
        $this->cors->handle();
    }

    protected function getRequestInstance()
    {
        return RequestFacade::getRequestInstance();
    }
}
