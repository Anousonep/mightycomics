<?php


namespace App\Controller;

use App\Repository\ComicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ComicRepository $repo){
        $comics = $repo->findAll();

        return $this->render('home.html.twig', [
            'title' => 'MIGHTY COMICS',
            'comics' => $comics,
        ]);
    }

}