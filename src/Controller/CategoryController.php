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

    #[Route('/categories/delete', name: 'category_delete')]
    public function delete( Request $request, CategoryRepository $repo, EntityManagerInterface $em): Response
    {
        $id = $request->getPayload()->get('id');
        $cat = $repo->find($id);

        if ($cat== null){
            throw $this->createNotFoundException();
        }
        
            $em->remove($cat);
            $em->flush();

        return $this->redirectToRoute('category_index');
       
    }

    #[Route('/categories/{id}', name: 'category_show')]
    public function show( $id, CategoryRepository $repo): Response
    {
        //dd($id);
        return $this->render('category/show.html.twig', [
            'category'=> $repo->find($id)
        ]);
    }

    #[Route('/categories/{id}/edit', name: 'category_edit')]
    public function edit( Category $cat, Request $request, EntityManagerInterface $em): Response
    {
        
        //dd($cat);
        $form = $this->createForm(CategoryType::class, $cat);
        $form->handleRequest($request);
        if($form->isSubmitted()){
                $em->flush();

        return $this->redirectToRoute('category_index');
       }
         return $this->render('category/edit.html.twig', [
                'form'=>$form
         ]);
    }

    
}
