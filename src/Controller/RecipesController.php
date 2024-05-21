<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recipes;
use App\Form\RecipesType;
use App\Repository\RecipesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recipes')]
class RecipesController extends AbstractController
{
    #[Route('/', name: 'app_recipes_index', methods: ['GET'])]
    public function index(RecipesRepository $recipesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('recipes/index.html.twig', [
            'recipes' => $recipesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipes_new', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipes();
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUser($this->getUser());

            $entityManager->persist($recipe);

            $entityManager->flush();

            return $this->redirectToRoute('app_recipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipes/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_recipes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipes $recipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipesType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipes_delete', methods: ['POST'])]
    public function delete(Request $request, Recipes $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipes_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}<\d++>', name: 'app_recipes_show', methods: ['GET'])]
    public function show($id,EntityManagerInterface $entityManager): Response
    {
        $recipe = $entityManager->getRepository(Recipes::class)->find($id);

        if (!$recipe) {
            throw $this->createNotFoundException('No recipe found for id '.$id);
        }

        return $this->render('recipes/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }
    #[Route('/search', name: 'app_recipes_search', methods: ['GET', 'POST'])]
    public function search(Request $request, RecipesRepository $recipesRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('search', SubmitType::class, ['label' => 'Search Recipes'])
            ->getForm();

        $form->handleRequest($request);

        $recipes = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredients = $form->get('ingredients')->getData();

            $recipes = $recipesRepository->findWithIngredients($ingredients);

            // Add logging here
            if (empty($recipes)) {
                error_log('No recipes found with the selected ingredients');
            } else {
                error_log('Found ' . count($recipes) . ' recipes with the selected ingredients');
            }
        }

        return $this->render('recipes/search.html.twig', [
            'form' => $form->createView(),
            'recipes' => $recipes,
        ]);
    }
}
