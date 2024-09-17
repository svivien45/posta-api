<?php

namespace App\Html;

use App\Repository\CountyRepository;
use App\Html\Response;

class Request
{
    static function handle(): void 
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "GET":
                self::getRequest();
                break;

            default:
                echo 'Unknown request type';
                break;
        }
    }

    private static function getRequest(): void
    {
        $resourceName = self::getResourceName();
        switch ($resourceName)
        {
            case 'counties':
                $db = new CountyRepository();
                $resourceId = self::getResourceId();
                $code = 200;
                if ($resourceId) {
                    $entity = $db->find($resourceId);
                    Response::response($ntity, $code);
                    break;
                }

                $entities = $db->getAll();
                if (empty($entities)){
                    $code = 404;
                }
                Response::response($entities, $code);
                break;

                default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . "not found");
        }
    }


    private static function getArrUri(string $requestUri): ?array{
        return explode("/", $requestUri) ?? null;
    }

    private static function getResourceName(): string
    {
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI']);
        $result = $arrUri[count($arrUri) - 1];
        if (is_numeric($result)){
            $result = $arrUri[count($arrUri) - 2];
        }
        return $result;
    }

    private static function getResourceId(): int{
        $arrUri = self::getArrUri($_SERVER['REQUEST_URI'])<
        $result = 0;
        if (is_numeric($arrUri[count($arrUri) - 1])){
            $result = $arrUri[count($arrUri) - 1];
        }
        return $result;
    }     
}