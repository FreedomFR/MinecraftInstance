<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Goutte\Client;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function Accueil(): Response
    {
        return $this->render('user/accueil.html.twig');
    }

    /**
     * @Route("/show/modpack/list", name="show_modpack_list")
     */
    public function showModPackList(Request $request): Response
    {
        $idMod = $request->request->get('idMod');
        $modList = [];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.curseforge.com/v1/mods/" . $idMod);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Accept: application/json',
            'x-api-key: $2a$10$euMxXrNcYb1P/THo4SMJ6OAwP3.I4YCts9vmX/bEpmduIDJiVmiN2'
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $data = curl_exec ($ch);

        if($data)
        {
            $prettyJson = (array) json_decode($data);

//            dump($prettyJson);
//            die();

            if($prettyJson['data'])
            {

                $tmpLastListMod = [];
                $lastFiles = $prettyJson['data']->latestFiles;

                foreach ($lastFiles as $lastFile)
                {
                    $urlServer = "";
                    if(isset($lastFile->serverPackFileId))
                    {
                        $serverPackFileId = $lastFile->serverPackFileId;

                        $ch2 = curl_init();
                        curl_setopt($ch2, CURLOPT_URL,"https://api.curseforge.com/v1/mods/" . $idMod . "/files/" . $serverPackFileId . "/download-url");
                        curl_setopt($ch2, CURLOPT_POST, 0);
                        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);

                        $data2 = curl_exec ($ch2);

                        if($data2)
                        {
                            $prettyJson2 = (array) json_decode($data2);
                            $urlServer = $prettyJson2['data'];
                        }

                        curl_close ($ch2);
                    }

                    $tmpL = [
                        'id' => $lastFile->id,
                        'date' => $lastFile->fileDate,
                        'display_name' => $lastFile->displayName,
                        'url_server' => $urlServer
                    ];

                    array_push($tmpLastListMod, $tmpL);
                }

                $modList = [
                    'id' => $prettyJson['data']->id,
                    'name' => $prettyJson['data']->name,
                    'list_mod' => $tmpLastListMod
                ];
            }
        }
        curl_close ($ch);

        return $this->render('user/tableModList.html.twig', [
            'mods' => $modList
        ]);
    }

}