<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task')]
    public function index(TaskRepository $repo): Response
    {

        // $tasks= $repo->findAll();
        // dd($tasks);

        return $this->render('task/index.html.twig', [
            'tasks'=> $repo->findAll()
        ]);
    }
}
