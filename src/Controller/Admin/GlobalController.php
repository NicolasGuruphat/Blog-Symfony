<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;




class GlobalController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {

        return $this->render('Admin/controlPanel.html.twig', []);
    }
}
