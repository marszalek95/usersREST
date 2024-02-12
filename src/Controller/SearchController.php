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
    #[Route('/handleSearch', name: 'handleSearch')] 
    public function searchUser(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('search', TextType::class)
            ->setAction($this->generateUrl('handleSearch'))
            
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->getData()['search'];
            
            return $this->redirect("/search/query/{$query}/1");
        }   

        return $this->render('searchBar.html.twig', [
            'search_form' => $form,
        ]);
    }
}
