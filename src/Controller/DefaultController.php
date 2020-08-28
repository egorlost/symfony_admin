<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{RedirectResponse, Response};
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @return RedirectResponse
     *
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('admin_home_page');
    }
}
