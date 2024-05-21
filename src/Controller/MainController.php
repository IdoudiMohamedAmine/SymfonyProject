<?php

namespace App\Controller;

use App\Repository\RecipesRepository;
use http\Client\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(RecipesRepository $recipesRepository, PaginatorInterface $paginator, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $query = $recipesRepository->findAll();
        $recipes = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('main/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
    #[Route('/admin', name: 'app_admin')]
    #[isGranted('ROLE_ADMIN')]
    public function admin(): Response
    {
        return $this->render('main/admin.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
