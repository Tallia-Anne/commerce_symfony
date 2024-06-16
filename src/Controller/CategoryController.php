<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // On va recupérer tous les éléments de la table Catégories
        $categories = $categoryRepository->findAll();
        // dd($categories);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Ajouter une categorie
    #[Route('/admin/category/new', name: 'app_category_new')]

    public function addCategory(EntityManagerInterface $entityManager, Request $request): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        //on va recuperer les élément de la requete    
        $form->handleRequest($request);

        if($form ->isSubmitted() && $form ->isValid()) {
        // dd($category);
        $entityManager->persist($category);
        // enregirer dans la base de données
        $entityManager->flush();
        $this->addFlash('success', 'votre catégorie a été ajouter');
         return $this-> redirectToRoute('app_category');
        }

        return $this->render('category/new.html.twig', ['form' => $form->createView()]);
        

    }
    // Mettre a jour
    #[Route('/admin/category/{id}/update', name: 'app_category_update')]
    public function update(Category $category, EntityManagerInterface $entityManager, Request $request): Response 
    {

         $form = $this->createForm(CategoryFormType::class, $category);
        //on va recuperer les élément de la requete    
        $form->handleRequest($request);

         if($form ->isSubmitted() && $form ->isValid()) {
        // enregirer dans la base de données
        $entityManager->flush();
         $this->addFlash('success', 'votre catégorie a été Modifier');
         return $this-> redirectToRoute('app_category');
        }

        return $this->render('category/update.html.twig', ['form' => $form->createView()]);

    }

    // Supprimer
    #[Route('/admin/category/{id}/delete', name: 'app_category_delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager): Response 
    {

    $entityManager->remove($category);
    $entityManager->flush();
    $this->addFlash('danger', 'votre catégorie a été supprimer');
    return $this-> redirectToRoute('app_category');
      

    }
}
