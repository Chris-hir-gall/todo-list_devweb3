<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category_index')]
    public function index(CategoryRepository $repo): Response
    {
        return $this->render('category/index.html.twig', [
            'categories'=> $repo->findAll(),
        ]);
    }

    #[Route('category/new', name: 'category_new')]
    public function newCategory(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

            if($form->isSubmitted()){
                $em->persist($category);
                $em->flush();

            return $this->redirectToRoute('category_index');
       }
        return $this->render('category/new.html.twig', [
            'form'=>$form
        ]);
    }

    #[Route('/categories/show', name: 'category_show')]
    public function show( $id): Response
    {
        dd($id);
       /* return $this->render('category/show.html.twig', [
            'categories'=> $repo->find($id),
        ]);*/
    }
}
