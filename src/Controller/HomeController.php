<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CatRepository;
use App\Repository\KittieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(KittieRepository $repository): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
            'cats' => $repository->findAll(),
        ]);
    }
}
