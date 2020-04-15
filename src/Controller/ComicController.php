<?php

namespace App\Controller;

use App\Entity\Comic;
use App\Form\ComicType;
use App\Repository\ComicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComicController extends AbstractController
{
    /**
     * @Route("/comics/{page}", name="comics_index", requirements={"page": "\d+"})
     */

    public function index(ComicRepository $repo, $page = 1 )
    {

        $limit = 15;

        $start = $page * $limit - $limit;
        // 1 * 15 = 15 - 15 = 0
        // 2 * 15 = 30 - 15 = 15

        $total = count($repo->findAll());

        $pages = ceil($total / $limit); // ceil arrondie au dessus

        $comics = $repo -> findBy([], ['id' => 'desc'], $limit, $start);

        return $this -> render('comic/index.html.twig', [
            'comics' => $comics,
            'pages' => $pages,
            'page' => $page
        ]);
    }


    /**
     * Permet d'afficher les détails d'un comic
     *
     * @Route("/comics/{slug}", name="comics_show")
     *
     * @return Response
     */

    public function show($slug, ComicRepository $repo)
    {
//        Recup du comic qui correspond au slug
        $comic = $repo -> findOneBySlug($slug);

        return $this -> render('comic/show.html.twig', [
           'comic' => $comic,
        ]);
    }

    /**
     * Permet d'effacer rapidement un comic
     *
     * @Route("/comics/{id}/remove", name="comics_remove")
     *
     * @Security("is_granted('ROLE_ADMIN')", message="Cette page n'est accessible que par l'admin!")
     *
     * @return Response
     *
     */
    public function remove(Comic $comic, EntityManagerInterface $manager){
        $manager->remove($comic);
        $manager->flush();

        return $this->redirectToRoute('comics_index');
    }
}
