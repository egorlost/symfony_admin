<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends AbstractController
{
    /**
     * Admin Home Page
     *
     * @Route("/index", name="admin_home_page")
     *
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/home.html.twig');
    }
}
