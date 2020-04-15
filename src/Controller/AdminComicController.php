<?php

namespace App\Controller;

use App\Entity\Comic;
use App\Entity\User;
use App\Form\ComicType;
use App\Repository\ComicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminComicController extends AbstractController
{
    /**
     * @Route("/admin/comics/{page}", name="admin_comics_index", requirements={"page": "\d+"})
     */
    public function index(ComicRepository $repo, $page = 1)
    {

        $limit = 10;

        $start = $page * $limit - $limit;
                // 1 * 10 = 10 - 10 = 0
                // 2 * 10 = 20 - 10 = 10
                // 3 * 10 = 30 - 10 = 20

        $total = count($repo->findAll());

        $pages = ceil($total / $limit); // ceil arrondie au dessus

        $comics = $repo -> findBy([], ['id' => 'desc'], $limit, $start);

        return $this->render('admin/comic/index.html.twig', [
            'comics' => $comics,
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet de créer un comic
     *
     * @Route("admin/comic/new", name="comics_create")
     *
     * @return Response
     */

    public function create(Request $request, EntityManagerInterface $manager)
    {

//     getForm permet de construire le formulaire
        $comic = new Comic();

        $form = $this -> createForm(ComicType::class, $comic);

//        permet de gérer la requête
        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comic);
            $manager->flush();

            $this -> addFlash(
                'success',
                "L'article <strong>{$comic->getTitle()}</strong> a bien été ajouté!"
            );

            return $this -> redirectToRoute('admin_comics_index');
//            ['slug' => $comic -> getSlug()]
        }

        return $this -> render('admin/comic/new.html.twig', [
            'form' => $form -> createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/comics/{id}/edit", name="admin_comics_edit")
     *
     * @param Comic $comic
     * @return Response
     */
    public function edit(Comic $comic, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(ComicType::class, $comic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($comic);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le comic <strong>{$comic->getTitle()}</strong> a bien été modifié!"
            );
        }

        return $this->render('admin/comic/edit.html.twig', [
           'comic' => $comic,
           'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un comic
     *
     * @Route("/admin/comics/{id}/delete", name="admin_comics_delete")
     *
     * @param Comic $comic
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comic $comic, EntityManagerInterface $manager){
        $id = $comic->getId();

        $manager->remove($comic);
        $manager->flush();

//        $this->addFlash(
//            'success',
//            "Le comic <strong>{$comic->getTitle()}</strong> a bien été supprimé"
//        );

//        return $this->redirectToRoute('admin_comics_index');
        return $this->json($id);
    }

}
