<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use App\Handler\DnsHandler;
use App\Handler\NeustarHandler;
use Whoops\Exception\ErrorException;

class PingHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $neustar = new NeustarHandler();
        $params = $request->getParsedBody();       
        $domains = explode(",", $params['domains']);
        
        if (empty($domains)){
            throw  new ErrorException("Domain can't be empty");
        }
                        
        $dns = new DnsHandler($domains);
        
        if (!$dns->sanitize()){
            throw  new ErrorException("Wrong Domain Definition");
        }                
        
        $criteria = str_ireplace(',', "','", $params['domains']);
        $sql = "select * from lookups where domain in ('" . $criteria . "')";
        $contents = $neustar->select($sql);
        
        if (count($contents) != count($domains)) {
            $contents = array_merge($contents, $dns->check($contents,$neustar));
        }
          return new JsonResponse($contents);
    }
}
