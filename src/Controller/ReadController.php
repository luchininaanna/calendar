<?php


namespace App\Controller;


use App\Calendar\Api\ApiQueryInterface;use Symfony\Component\HttpFoundation\RequecreateUserst;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReadController extends AbstractController
{
    private ApiQueryInterface $api;

    public function __construct(ApiQueryInterface $api)
    {
        $this->api = $api;
    }

    public function getAllUsers(Request $request): Response
    {
        $json = [];
        foreach ($this->api->getAllUsers() as $user)
        {
            $json[] = $user->asAssoc();
        }
        return $this->json($json);
    }
}