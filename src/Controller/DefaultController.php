<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController  
{
    /**
    * @Route("/login");
    */
    public function login()
    {
      return $this->render('login.html.twig', [
        
    ]);

    }
    /**
    * @Route("/");
    */
    public function listeArticle()
    {
      return $this->render('User/article.html.twig', [
        
    ]);
    }
}

