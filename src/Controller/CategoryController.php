<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    // protected $categoryRepository;
    // public function __construct(CategoryRepository $categoryRepository)
    // {
    //     $this->categoryRepository = $categoryRepository;
    // }
    // public function renderMenuList(){
    //     //aller chercher les categorie
    //     $categories = $this->categoryRepository->findAll();

    //     return $this->render('category/_menu.html.twig', [
    //         'categories' => $categories
    //     ]);

    // }
    #[Route('/admin/category/create', name: 'category_create')]
    public function create(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();

        return $this->render('category/create.html.twig', [ 
            'formView' => $formView           
        ]);
    }

    #[Route('/admin/category/{id}/edit', name:'category_edit')]
    public function edit($id, Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $category = $categoryRepository->findOneBy([
            'id' => $id
        ]);

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();


        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
