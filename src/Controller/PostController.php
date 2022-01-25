<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController  
{
    /**
     * @Route("/post/{id}",name="post");
     */

    public function post($id)
    {
      
      return $this->render('User/post.html.twig', [
        'id'=>$id,
    ]);

    }
    /**
    * @Route("/");
    */
    public function listPost()
    {
      return $this->render('User/listPost.html.twig', [
        
    ]);
    }
}

