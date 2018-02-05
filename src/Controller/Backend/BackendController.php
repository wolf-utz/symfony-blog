<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Module\Module;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BackendController.
 */
class BackendController extends Controller
{
    /**
     * @Route("/backend", name="backend_index")
     */
    public function indexAction()
    {
        $modules = [
            new Module("Posts", "Manage your posts here.", "newspaper", "backend_post_list")
        ];

        return $this->render('backend/index.html.twig', [
            "modules" => $modules
        ]);
    }
}