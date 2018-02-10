<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConfigurationController.
 */
class ConfigurationController extends Controller
{
    /**
     * @Route("/backend/configuration", name="backend_configuration")
     */
    public function index()
    {
        return $this->render('layouts/module.html.twig', []);
    }
}