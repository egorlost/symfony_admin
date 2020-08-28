<?php declare(strict_types=1);

namespace App\Controller;

use App\DBAL\PublishedStatusEnum;
use App\Entity\Tag;
use App\Entity\TagInEntity;
use App\Service\ControllerServiceTrait;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ControllerTrait
{
    use ControllerServiceTrait {
        repository as parentRepository;
    }

    /**
     * Unifies Json Array for every api method
     *
     * @param bool $success
     * @param string $message
     * @param array|null $data
     * @return array
     */
    protected function prepareJsonArr(bool $success, string $message, array $data = null): array
    {
        $result = ['success' => $success, 'message' => $message];

        if ($data !== null) {
            $result = array_merge($result, $data);
        }

        return $result;
    }

    /**
     * It returns repository for an entity class.
     *
     * @param string $entityName
     *
     * @return ServiceEntityRepository
     */
    protected function repository(string $entityName): ObjectRepository
    {
        if ($this->em === null) {
            $this->setEm($this->getDoctrine()->getManager());
        }

        return $this->parentRepository($entityName);
    }

    /**
     * Delete any object in db (set deleted = true)
     *
     * @param Request $r
     * @return JsonResponse
     *
     * @Route("/delete-entity", name="delete_entity")
     */
    public function deleteAction(Request $r): JsonResponse
    {
        $entity = $this->repository('App:' . $r->query->get('entityName'))->find($r->query->get('id'));
        if (!$entity) {
            return new JsonResponse($this->prepareJsonArr(false, 'Entity does not found! '));
        }

        $entity->setDeleted(true);

        if (method_exists($entity, 'getTag')) {
            $tag = $entity->getTag();
            $tag->setDeleted(true);
        }

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->prepareJsonArr(true, ''));
    }

    /**
     * Return form errors separated by comma
     *
     * @param FormInterface $form
     * @param bool $asArray
     * @return string|array
     */
    protected function getFormErrors(FormInterface $form, bool $asArray = false)
    {
        $formErrors = $form->getErrors(true);
        $errors = [];
        foreach ($formErrors as $key => $error) {
            $errors[$key] = $error->getMessage();
        }

        if ($asArray) {
            return $errors;
        }

        return implode(',', $errors);
    }

    private function validateEntity(FormInterface $form, ValidatorInterface $validator): void
    {
        $validationGroups = $form->getData()->getStatus() === PublishedStatusEnum::VALUE_PUBLISHED ? ['Default', 'seo'] : ['Default'];

        foreach ($form->get('translations') as $child) {
            if (!$child->isEmpty()) {

                $data = $child->getData();
                $errors = $validator->validate($data, null, $validationGroups);

                foreach ($errors as $error) {
                    $path = $error->getPropertyPath();
                    if (strripos($path, '.')) {
                        [$pathForm, $pathValue] = explode('.', $path);

                        preg_match('/(.*?)\[(.*?)]/', $pathForm, $output);

                        $form->get('translations')
                            ->get($child->getName())
                            ->get($output[1])[$output[2]]->get($pathValue)
                            ->addError(new FormError($error->getMessage()));
                    } else {
                        $form->get('translations')
                            ->get($child->getName())
                            ->get($path)
                            ->addError(new FormError($error->getMessage()));
                    }
                }
            }
        }
    }

    private function entityTagUpdate($entity, $tagType): Tag
    {
        $tag = $entity->getTag();

        if (!$tag) {
            $tag = (new Tag())
                ->setDeleted(false)
                ->setEntityId($entity->getId())
                ->setType($tagType);
            $entity->setTag($tag);
        }

        $tag->setStatus($entity->getStatus());

        foreach ($entity->getTranslations() as $translation) {
            $tag->translate($translation->getLocale())->setName($translation->getName());
        }

        $tag->mergeNewTranslations();

        return $tag;
    }

    private function getEntityTags($entity, $entityType)
    {
        return $this->getDoctrine()->getRepository(TagInEntity::class)->getEntityTags($entity->getId(), $entityType);
    }

    /**
     * @param array $result
     * @return array
     */
    public function prepareObjectDataForSelect(array $result): array
    {
        $data = [];
        foreach ($result as $item) {

            $suffix = '';
            if ($item instanceof Tag) {
                $suffix = ' (' . $item->getType() . ')';
            }

            $data[] = [
                'id' => $item->getId(),
                'text' => $item->getName() . $suffix
            ];

        }
        return $data;
    }
}
