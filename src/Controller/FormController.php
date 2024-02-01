<?php
// src/Controller/FormController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Controller\UserController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FormController extends AbstractController
{
    /**
     *@Route("/adduser",  name="adduser")
     */
    public function addUserForm(Request $request)
    {
        $user = new User();
        $message = [];

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $result_arr = [
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'status' => $user->status,
                
            ];
            $result_arr;

            $usercontroller = new UserController();
            $meta = $usercontroller->addUser($result_arr);
            if($meta['code'] == 201)
            {
                $message = ['alert' => false, 'message' => 'User added succesfully!'];
            }
            else
            {
                $message = ['alert' => true, 'message' => $meta['code']];
            }
        }

        return $this->render('adduser.html.twig', [
            'user_form' => $form->createView(),
            'message' => $message
        ]);
    }

    /**
     *@Route("/edituser/id/{id}",  name="edituser")
     */
    public function editUserForm(Request $request, $id)
    {
        $user = new User();
        $message = [];

        $usercontroller = new UserController();
        $userdata = $usercontroller->idUser($id);
        

        $user->name = $userdata['data']['name'];
        $user->email = $userdata['data']['email'];
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $result_arr = [
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->gender,
                'status' => $user->status,
                
            ];
           
            $usercontroller = new UserController();
            $meta = $usercontroller->editUser($result_arr, $id);
            if($meta['code'] == 200)
            {
                $message = ['alert' => false, 'message' => 'User updatet succesfully!'];
            }
            else
            {
                $message = ['alert' => true, 'message' => $meta['code']];
            }

        }

        return $this->render('edituser.html.twig', [
            'user_form' => $form->createView(),
            'message' => $message
        ]);
    }
    
}