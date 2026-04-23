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

        $this->addFlash('success','La tache a été ajoute avec succès.');
        return $this->redirectToRoute('app_task');
       }

        return $this->render('task/index.html.twig', [
            'tasks'=> $repo->findBy([],['createAt'=>'DESC']),
            'form'=>$form
        ]);
    }

    #[Route('/task/complete', name: 'task_complete')]
    public function taskComplete(Request $request, TaskRepository $repo, EntityManagerInterface $em): Response
{       $id = $request->getPayload()->get('id'); 
        $request->getPayload()->get('isDone');

       $task = $repo->find($id);
       if($task == null){
            throw $this->createNotFoundException();
       }
       $task->setIsDone(!$task->isDone());
        $em->flush();

        $this->addFlash('success','La tache a été completée avec succès.');
        return $this->redirectToRoute('app_task');
    }
    #[Route('/tasks/delete', name: 'task_delete')]
    public function delete(Request $request, TaskRepository $repo, EntityManagerInterface $em): Response
    {
        $id = $request->getPayload()->get('id');
        $task = $repo->find($id);

        if ($task === null) {
            throw $this->createNotFoundException();
        }

        $em->remove($task);
        $em->flush();

        $this->addFlash('success','La categorie a été suprimée avec succès.');
        return $this->redirectToRoute('app_task');
    }
}
