<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerTrait;
use App\DBAL\TagTypeEnum;
use App\Entity\Tag;
use App\Entity\TagInEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/tag")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class TagController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/find-tag", name="findTag"))
     */
    public function findTag(Request $request): JsonResponse
    {
        $data = [];
        $keyWord = mb_strtolower(trim($request->query->get('keyWord')));

        $result = $this->getDoctrine()->getRepository(Tag::class)->findTagByKeyword($keyWord);

        if (count($result)) {
            $data = $this->prepareObjectDataForSelect($result);
        }

        return new JsonResponse($data);
    }

    /**
     * @param Tag $tag
     * @param $entityId
     * @param $entityType
     * @return JsonResponse
     *
     * @Route("/add-tag-to-entity/{entityType}/{entityId}/{tag}", name="addTagToEntity")
     */
    public function addTagToEntity(Tag $tag, $entityId, $entityType): JsonResponse
    {
        $message = '';

        if ($this->tinExists($tag, $entityId, $entityType) === null) {
            $this->tiePersist($tag, $entityId, $entityType);
            $message = 'Тег <b>[' . $tag->getName() . ']</b> успішно прив`язаний.';
        }

        return new JsonResponse($this->prepareJsonArr(true, $message));
    }

    public function tinExists(Tag $tag, $entityId, $entityType)
    {
        return $this->getDoctrine()->getRepository(TagInEntity::class)->tinExists($tag, $entityId, $entityType);
    }

    public function tiePersist(Tag $tag, $entityId, $entityType): bool
    {
        $em = $this->getDoctrine()->getManager();

        $tie = (new TagInEntity())
            ->setTag($tag)
            ->setEntityId((int)$entityId)
            ->setEntityType($entityType);

        $em->persist($tie);

        if (in_array($entityType, TagTypeEnum::getValues(), true)) {
            $entity = $this->getDoctrine()->getRepository('App:' . $entityType)->find($entityId);

            if ($entity) {
                $tie = (new TagInEntity())
                    ->setTag($entity->getTag())
                    ->setEntityId($tag->getEntity()->getId())
                    ->setEntityType($tag->getType());

                $em->persist($tie);
            }
        }

        $em->flush();

        return true;
    }

    /**
     * @param Tag $tag
     * @param $entityId
     * @param $entityType
     * @return JsonResponse
     *
     * @Route("/remove-tag-from-entity/{entityType}/{entityId}/{tag}", name="removeTagFromEntity")
     */
    public function removeTagFromEntity(Tag $tag, $entityId, $entityType): JsonResponse
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();
        $tin = $this->getDoctrine()->getRepository(TagInEntity::class)->findOneBy(array
        (
            'tag' => $tag,
            'entityId' => $entityId,
            'entityType' => $entityType,
            'deleted' => false,
        ));

        if ($tin !== null) {
            $tin->setDeleted(true);
            $em->persist($tin);

            $em->flush();
            $message = "Ви успішно видалили зв'язок тега <b>[" . $tag->getName() . "]</b> з даною сутністю.";
        }

        return new JsonResponse($this->prepareJsonArr(true, $message));
    }

    /**
     * @param Request $request
     *
     * @throws
     *
     * @return JsonResponse
     *
     * @Route("/search-tag-entity", name="search_tag_entity")
     */
    public function searchTagEntity(Request $request): JsonResponse
    {
        $tagId = (int)($request->request->get('tagId'));
        $tag = $this->getDoctrine()->getRepository(Tag::class)->findById($tagId);
        $data = [];

        if ($tag) {
            /** @var Tag $tag */
            $data['success'] = true;
            $data['name'] = $tag->getEntity()->getName();

            return new JsonResponse($data);
        }

        return new JsonResponse(['success' => false]);
    }
}
