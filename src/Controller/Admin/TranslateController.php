<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/translate")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class TranslateController extends AbstractController
{
    use ControllerTrait;

    /**
     * @return JsonResponse
     *
     * @Route("/cache-clear", name="translate_cache_clear")
     */
    public function cacheClear(): JsonResponse
    {
        $translationsCacheDir = $this->getParameter('kernel.cache_dir') . '/translations';

        if (is_dir($translationsCacheDir)) {
            (new Filesystem())->remove($translationsCacheDir);
        }

        return new JsonResponse($this->prepareJsonArr(true, ''));
    }
}
