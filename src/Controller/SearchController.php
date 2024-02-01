<?php
// src/Controller/SearchController.php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;


class SearchController extends AbstractController
{ 
    public function searchUserForm(Request $request)
    {
        $form = $this->createFormBuilder(null)
            ->add('search', TextType::class)
            
            ->getForm();

        return $this->render('searchBar.html.twig', [
            'search_form' => $form->createView()
        ]);
    }

    #[Route('/', name: 'handleSearch', methods: ['POST'])]
    public function handleSearch(Request $request): Response
    {      
        $query = $request->request->get('form')['search'];
        
        return $this->redirect("search/query/{$query}/1");
    }
}
