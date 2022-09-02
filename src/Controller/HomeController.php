<?php

namespace App\Controller;

use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Goutte\Client;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function Accueil(): response
    {
        return $this->render('user/accueil.html.twig');
    }

    /**
     * @Route("/get/url", name="get_url")
     */
    public function getUrl(Request $request)
    {
        $headers = array(
            'Accept' => 'application/json',
            'x-api-key' => '$2a$10$euMxXrNcYb1P/THo4SMJ6OAwP3.I4YCts9vmX/bEpmduIDJiVmiN2',
        );

        $client = new Client();

        // Define array of request body.
        $request_body = array();

        try {
            $response = $client->request('GET','https://api.curseforge.com/v1/mods/{modId}/files/3947163', array(
                    'headers' => $headers,
                    'json' => $request_body,
                )
            );
            print_r($response->getBody()->getContents());
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // handle exception or api errors.
            print_r($e->getMessage());
        }

        dump($response);
        die();

        return new JsonResponse($url);
    }

}