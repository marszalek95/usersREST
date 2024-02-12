<?php
// src/Controller/FormController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Controller\UserController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class FormController extends AbstractController
{
    #[Route('/adduser', name: 'adduser')]
    public function addUserForm(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $result_arr = [
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'status' => $user->status,
                
            ];

            $usercontroller = new UserController();
            $meta = $usercontroller->addUser($result_arr);

            if($meta['code'] == 201) {
                $this->addFlash('success', 'User created successfully!');
            }
            else {
                $this->addFlash('fail', 'Something went wrong');
            }
                     
            return $this->redirectToRoute('userinfo', ['id' => $meta['data']['id']]);
        }

        return $this->render('adduser.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edituser/id/{id}', name: 'edituser')]
    public function editUserForm(Request $request, $id)
    {
        $user = new User();

        $usercontroller = new UserController();
        $userdata = $usercontroller->idUser($id);
        

        $user->name = $userdata['data']['name'];
        $user->email = $userdata['data']['email'];
        $user->gender = $userdata['data']['gender'];
        $user->status = $userdata['data']['status'];
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $result_arr = [
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'status' => $user->status,
                
            ];
           
            $usercontroller = new UserController();
            $meta = $usercontroller->editUser($result_arr, $id);
            if($meta['code'] == 200) {
                $this->addFlash('success', 'User updated successfully!');
            }
            else {
                $this->addFlash('fail', 'Something went wrong');
            }

            return $this->redirectToRoute('userinfo', ['id' => $id]);

        }

        return $this->render('edituser.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }
    
}