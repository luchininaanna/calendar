<?php


namespace App\Controller;

use App\Calendar\Api\ApiCommandInterface;
use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Input\CreateUserInput;
use App\Controller\Mapper\CreateUserRequestMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WriteController extends AbstractController
{
    private ApiCommandInterface $api;

    public function __construct(ApiCommandInterface $api)
    {
        $this->api = $api;
    }

    public function addUser(Request $request): Response
    {
        $userInput = CreateUserRequestMapper::buildInput($request->getContent());
        try
        {
            $this->api->createUser($userInput);
            return $this->json(['result' => 'User created']);
        }
        catch (UserAlreadyExistException $e)
        {
           return $this->json(['result' => 'User already exist'], 400);
        }
    }
}