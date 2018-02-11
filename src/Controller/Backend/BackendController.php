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
            new Module("Posts", "Manage your posts here.", "pencil-square-o", "backend_post_list"),
            new Module("Comments", "Manage the comments", "comment", "backend_configuration_index"),
            new Module("Tags", "Manage your tags here", "tag", "backend_configuration_index"),
            new Module("Users", "Manage your users here", "users", "backend_configuration_index"),
            new Module("Statistics", "Overview general statistics", "bar-chart", "backend_configuration_index"),
            new Module("Configurations", "Configure the system", "cogs", "backend_configuration_index"),
        ];

        return $this->render('backend/index.html.twig', [
            "modules" => $modules
        ]);
    }
}