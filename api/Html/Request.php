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

            case "DELETE":
                self::deleteRequest();
                break;

            case "PUT":
                self::putRequest();
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

    private static function posRequest(){
        $resource = self::getResourceName();
        switch ($resource){
            case 'counties':
                $data = self::getRequestData();
                if (isset($data['name'])) {
                    $db = new CountyRepository();
                    $newId = $db->create($data);
                    $code = 201;
                    if (!$newId) {
                        $code = 400;
                    }
                }
                Response::response(['id' => $newId], $code);

            default: Response::response([], 404, $_SERVER['REQUEST_URI'] . "not found"); 
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

    private static function getRequestData(): ?array{
        return json_decode(file_get_contents("php://input"), true);
    }

    private static function deleteRequest(){
        $id = self::getResourceId();
        if (!$id) {
            Response::response([], 400, Response::STATUSES[400]);
            return;
        }
        $resourceName = self::getResourceName();
        switch ($resourceName){
            case 'counties':
                $code = 404;
                $db = new CountyRepository();
                $result = $db->delete($id);
                if ($result){
                    $code = 204;
                }
                Response::response([], $code);
                break;
            default:
                Response::response([], 404, $_SERVER['REQUEST_URI'] . "not found");
        }
    }
    private static function putRequest(){
        $resource = self::getResourceName();
        switch ($resource){
            case 'counties':
                $data = self::getRequestData();
                $id = $data['id'];
                $db = new CountyRepository();
                $entity = $db->find($id);
                $code = 404;
                if ($entity){
                    $result = $db->update($id, ['name' => $data['name']]);
                    if ($result){
                        $code = 201;
                    }
                }
                Response::response([], $code);
                break;
            default:
                Response::response([], 404, "$uri not found");
        }
    }
}