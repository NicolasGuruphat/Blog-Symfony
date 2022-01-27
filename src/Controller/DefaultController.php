<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", "index")
     */
    public function index()
    {
        $response = $this->forward('App\Controller\User\CategoryController::showCategories', []);
        return $response;
    }
}
