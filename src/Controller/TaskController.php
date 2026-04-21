<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task')]
    public function index(Request $request, EntityManagerInterface $em, TaskRepository $repo): Response
    {

       $task = new Task;
       $form = $this->createForm(TaskType::class, $task);
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){
        $task->setIsDone(false);
        $task->setCreateAt(new \DateTimeImmutable());
        $em->persist($task);
        $em->flush();

        return $this->redirectToRoute('app_task');
       }

        return $this->render('task/index.html.twig', [
            'tasks'=> $repo->findBy([],['createAt'=>'DESC']),
            'form'=>$form
        ]);
    }
}
