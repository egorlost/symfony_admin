<?php

namespace App\Controller\Admin;

use App\Controller\ControllerTrait;
use App\DBAL\PublishedStatusEnum;
use App\DBAL\TagTypeEnum;
use App\Entity\Repository\StoryRepository;
use App\Entity\Story;
use App\Form\StoryType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/story")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class StoryController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param StoryRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route("/index", name="story_index")
     */
    public function index(Request $request, StoryRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->getAll([], true),
            $request->query->get('page', 1)/*page number*/,
            20/*limit per page*/
        );

        return $this->render('admin/story/index.html.twig', [
            'items' => $pagination,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     * @Route("/new", name="story_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $story = (new Story())
            ->setStatus(PublishedStatusEnum::VALUE_UNPUBLISHED)
            ->setDeleted(true);

        $entityManager->persist($story);
        $entityManager->flush();

        return $this->redirectToRoute('story_edit', ['id' => $story->getId()]);
    }

    /**
     * @param Story $story
     * @return Response
     *
     * @Route("/{id}", name="story_show")
     */
    public function show(Story $story): Response
    {
        return $this->render('admin/story/show.html.twig', [
            'story' => $story,
        ]);
    }

    /**
     * @param Request $request
     * @param Story $story
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @return Response
     *
     * @Route("/{id}/edit", name="story_edit")
     */
    public function edit(Request $request,
                         Story $story,
                         EntityManagerInterface $entityManager,
                         ValidatorInterface $validator): Response
    {

        if ($story->isEmptyRecord()) {
            $story->setDeleted(false);
        }

        $form = $this->createForm(StoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->validateEntity($form, $validator);

            if ($form->isValid()) {
                $tag = $this->entityTagUpdate($story, TagTypeEnum::VALUE_STORY);
                $entityManager->persist($story);
                $entityManager->persist($tag);

                $entityManager->flush();

                return $this->redirectToRoute('story_index');
            }
        }

        return $this->render('admin/$story/edit.html.twig', [
            'story' => $story,
            'form' => $form->createView(),
            'errors' => $this->getFormErrors($form, true),
            'entityTags' => $this->getEntityTags($story, TagTypeEnum::VALUE_STORY),
        ]);
    }
}
