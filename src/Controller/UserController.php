<?php
// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\FormController;
use Symfony\Component\BrowserKit\Request;

class UserController extends AbstractController
{
        #[Route('/users/{page}', name: 'users', requirements: ['page' => '\d+'])]
        public function getUsers(int $page = 1): Response
        {
            $url = "https://gorest.co.in/public-api/users?page={$page}";

            $client = HttpClient::create();

            $response = $client->request('GET', $url);
            $response_arr = $response->toArray();

            return $this->render('users.html.twig', 
            [
                'users' => $response_arr['data'],
                'meta' => $response_arr['meta']['pagination']
            ]);
        }

        #[Route('/search/query/{query}/{page}', name: 'search', requirements: ['page' => '\d+'])]
        public function findUsers($query, int $page = 1): Response
        {
            $url = "https://gorest.co.in/public-api/users?name={$query}&_format=json&access-token=6d62432c63d380689db6029841266954fa8bc4c2af2a183c0cf27b0365c1dab2";

            $client = HttpClient::create();

            $response = $client->request('GET', $url);
            $response_arr = $response->toArray();

            return $this->render('search.html.twig', 
            [
                'users' => $response_arr['data'],
                'meta' => $response_arr['meta']['pagination'],
                'query' => $query
            ]);         
        }

        #[Route('/userinfo/id/{id}', name: 'userinfo')]
        public function infoUser(int $id): Response
        {
        
            $response_arr = $this->idUser($id);

            return $this->render('userinfo.html.twig', 
            [
                'user' => $response_arr['data'],
            ]);         
        }

        public function idUser(int $id)
        {
            $url = "https://gorest.co.in/public-api/users/{$id}?&_format=json&access-token=6d62432c63d380689db6029841266954fa8bc4c2af2a183c0cf27b0365c1dab2";

            $client = HttpClient::create();

            $response = $client->request('GET', $url);
            $response_arr = $response->toArray();

            return $response_arr;
        }

        public function addUser($body)
        {
            $url = "https://gorest.co.in/public-api/users";

            $client = HttpClient::create();

            $response = $client->request('POST', $url, [
                'verify_host' => false,
                'headers' => ['Authorization' => 'Bearer 6d62432c63d380689db6029841266954fa8bc4c2af2a183c0cf27b0365c1dab2'], 
                'json' => $body         
            ]);
            
            $response_arr = $response->toArray();

            return $response_arr;
        }

        public function editUser($body, $id)
        {
            $url = "https://gorest.co.in/public-api/users/{$id}";

            $client = HttpClient::create();

            $response = $client->request('PATCH', $url, [
                'verify_host' => false,
                'headers' => ['Authorization' => 'Bearer 6d62432c63d380689db6029841266954fa8bc4c2af2a183c0cf27b0365c1dab2'], 
                'json' => $body         
            ]);
            
            $response_arr = $response->toArray();

            return $response_arr;
        }

        #[Route('/deleteuser/id/{id}', name: 'deleteuser')]
        public function deleteUser($id)
        {
            $url = "https://gorest.co.in/public-api/users/{$id}";

            $client = HttpClient::create();

            $response = $client->request('DELETE', $url, [
                'verify_host' => false,
                'headers' => ['Authorization' => 'Bearer 6d62432c63d380689db6029841266954fa8bc4c2af2a183c0cf27b0365c1dab2'],       
            ]);
            
            $response_arr = $response->toArray();
            if($response_arr['code'] == 204) {
                $this->addFlash('success', 'User deleted successfully!');
            }
            else {
                $this->addFlash('fail', 'Something went wrong');
            }

            return $this->redirectToRoute('users');
        }       
}