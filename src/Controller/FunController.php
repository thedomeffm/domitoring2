<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FunController extends AbstractController
{
    /**
     * @Route("/secure/love_dome", name="love_dome")
     */
    public function index()
    {
        return $this->render('fun/dome.html.twig', [
            // ---
        ]);
    }
}
