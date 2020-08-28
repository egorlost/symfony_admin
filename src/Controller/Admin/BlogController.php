<?php


namespace App\Controller\Admin;

use App\Controller\ControllerTrait;
use App\DBAL\PublishedStatusEnum;
use App\DBAL\TagTypeEnum;
use App\Entity\Blog;
use App\Entity\Repository\BlogRepository;
use App\Form\BlogType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/blog")
 * @Security("is_granted('ROLE_ADMIN')")
 *
 */
class BlogController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param BlogRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route("/index", name="blog_index")
     */
    public function index(Request $request, BlogRepository $repository, PaginatorInterface $paginator): Response
    {
        $filter = [];
        $pagination = $paginator->paginate(
            $repository->getAll($filter, true),
            $request->query->get('page', 1)/*page number*/,
            20/*limit per page*/
        );

        return $this->render('admin/blog/index.html.twig', [
            'items' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @throws \Exception
     *
     * @Route("/new", name="blog_new")
     */
    public function new(Request $request): Response
    {
        $blog = (new Blog())
            ->setDeleted(true)
            ->setPublishDate(new \DateTime())
            ->setStatus(PublishedStatusEnum::VALUE_UNPUBLISHED);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($blog);
        $entityManager->flush();

        return $this->redirectToRoute('blog_edit', ['id' => $blog->getId()]);
    }

    /**
     * @param Request $request
     * @param Blog $blog
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @Route("/{id}/edit", name="blog_edit")
     */
    public function edit(Request $request,
                         Blog $blog,
                         EntityManagerInterface $entityManager,
                         ValidatorInterface $validator): Response
    {
        if ($blog->isEmptyRecord()) {
            $blog->setDeleted(false);
        }

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateEntity($form, $validator);

            if ($form->isValid()) {
                $entityManager->persist($blog);
                $entityManager->flush();

                return $this->redirectToRoute('blog_index');
            }
        }

        return $this->render('admin/blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'errors' => $this->getFormErrors($form, true),
            'entityTags' => $this->getEntityTags($blog, TagTypeEnum::VALUE_BLOG),
        ]);
    }

    /**
     * @param Blog $blog ,
     * @return Response
     *
     * @Route("/{id}", name="blog_show")
     */
    public function show(Blog $blog): Response
    {
        return $this->render('admin/blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }


}
