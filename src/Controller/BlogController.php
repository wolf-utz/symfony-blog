<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController.
 */
class BlogController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render("blog/index.html.twig",[]);
    }
}