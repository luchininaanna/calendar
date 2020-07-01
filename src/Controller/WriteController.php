<?php


namespace App\Controller;

use App\Calendar\Api\ApiCommandInterface;
use App\Calendar\Api\Exception\UserAlreadyExistException;
use App\Calendar\Api\Input\CreateUserInput;
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
        try
        {
            $this->api->createUser(new CreateUserInput("ivan", "uskov", "ivan", "yurievich"));
            return new Response('Пользователь создан');
        }
        catch (UserAlreadyExistException $e)
        {
           return new Response("User already exist", 400);
        }
    }
}