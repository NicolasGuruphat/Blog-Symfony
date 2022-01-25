<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController  
{
    /**
     * @Route("/article/{id}",name="article");
     */

    public function article($id)
    {
      
      return $this->render('User/article.html.twig', [
        'id'=>$id,
    ]);

    }
    /**
    * @Route("/");
    */
    public function listeArticle()
    {
      return $this->render('User/listeArticle.html.twig', [
        
    ]);
    }
}

