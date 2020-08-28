<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\ControllerTrait;
use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use FOS\UserBundle\Model\UserManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * User controller.
 *
 * @Route("/user")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserController extends AbstractController
{
    use ControllerTrait;

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param int $limit
     * @return Response
     *
     * @Route("/index", name="user_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, $limit = 20): Response
    {
        $name = $request->query->get('name');

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findUser($name);

        $pagination = $paginator->paginate(
            $users,
            $request->query->get('page', 1)/*page number*/,
            $limit/*limit per page*/
        );

        return $this->render('admin/user/index.html.twig', array
        (
            'users' => $pagination
        ));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/new", name="user_new")
     */
    public function new(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups' => ['User', 'Registration']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $role = $request->request->get('rolesList');
            $user->setRoles(array($role));
            $user->setEnabled(true);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
            'roleList' => $this->getRole($user->getRoles()[0]),
            'isFormSubmitted' => $form->isSubmitted(),
            'errors' => $this->getFormErrors($form, true),
            'pageName' => 'Створення користувача'
        ));
    }

    /**
     * @param Request $request
     * @param User $user
     * @param UserManagerInterface $userManager
     * @return RedirectResponse|Response
     *
     * @Route("/{id}/edit", name="user_edit")
     */
    public function edit(Request $request, User $user, UserManagerInterface $userManager)
    {
        $editForm = $this->createForm(UserType::class, $user, ['validation_groups' => ['User', 'Profile']]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $role = $request->request->get('rolesList');

            $user->setRoles(array($role));
            $user->setEnabled(true);

            $userManager->updateUser($user);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
            'roleList' => $this->getRole($user->getRoles()[0]),
            'isFormSubmitted' => $editForm->isSubmitted(),
            'errors' => $this->getFormErrors($editForm, true),
            'pageName' => 'Редагування користувача'
        ));
    }

    /**
     * @param $role
     *
     * @return array
     */
    private function getRole($role): array
    {
        $rolesTitle = [];
        $allRoles = $this->repository(Role::class)->findAll();

        foreach ($allRoles as $k => $v) {
            /**
             * @var $v Role
             */
            if ($v->getTitle() === $role) {
                $rolesTitle[0] = $role;
            } else {
                $rolesTitle[$k + 1] = $v->getTitle();
            }
        }

        ksort($rolesTitle);

        return $rolesTitle;
    }
}
