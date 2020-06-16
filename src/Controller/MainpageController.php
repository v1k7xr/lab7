<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainpageController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index()
    {
        return $this->render('mainpage/index.html.twig', [
            'controller_name' => 'MainpageController',
        ]);
    }
}
