<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\AdminAccountType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users/{page}", name="admin_users_index", requirements={"page": "\d+"})
     *
     */
    public function index(UserRepository $repo, $page = 1)
    {
        $limit = 10;

        $start = $page * $limit - $limit;
        // 1 * 10 = 10 - 10 = 0
        // 2 * 10 = 20 - 10 = 10
        // 3 * 10 = 30 - 10 = 20

        $total = count($repo->findAll());

        $pages = ceil($total / $limit); // ceil arrondie au dessus

        $users = $repo -> findBy([], ['id' => 'desc'], $limit, $start);

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'succes',
                "Les informations de l'utilisateur <strong>{$user->getFullName()}</strong> ont bien été modifiées!"
            );
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un user
     *
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(User $user, EntityManagerInterface $manager){
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur' <strong>{$user->getFullName()}</strong> a bien été supprimé"
        );

        return $this->redirectToRoute('admin_users_index');
    }
}
