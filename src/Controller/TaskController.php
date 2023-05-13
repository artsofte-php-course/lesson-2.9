<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Security\Voter\ProjectVoter;
use App\Service\TelegramNotifier;
use App\Type\TaskFilterType;
use App\Type\TaskType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    public function create($id, $key, Request $request, ManagerRegistry $doctrine, TelegramNotifier $telegram)
    {
        /** Project $project */
        $project = $doctrine->getRepository(Project::class)->find($id);
        
        if ($project === null) {
            return $this->createNotFoundException(
                sprintf("Project with id %d not found", $id)
            );
        }

        if (!$this->isGranted(ProjectVoter::CREATE_TASK, $project)) {
            throw $this->createAccessDeniedException();
        }


        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $project->addTask($task);

            $doctrine->getManager()->persist($task);
            $doctrine->getManager()->flush();

            $telegram->notify(sprintf(
                'New task created %s', $task->getName()
            ));

            return $this->redirectToRoute('project_by_id', [
                'key' => $project->getSlug(),
                'id' => $project->getId()
            ]);
        }

        return $this->render(
            'tasks/create.html.twig', [
                'form' => $form->createView()
            ]
        );

    }
    
    public function list(TaskRepository $taskRepository, Request $request)
    {
       
        $filter = $this->createForm(TaskFilterType::class, [], [
            'method' => 'GET'
        ]);

        $filter->handleRequest($request);

        if ($filter->isSubmitted() && $filter->isValid()) {
            
            $filterData = $filter->getData();
            $tasks = $taskRepository->findByFilter($filterData);

        } else {
            $tasks = $taskRepository->findAll();
        }

        return $this->render('tasks/list.html.twig', [
            'tasks' => $tasks,
            'form' => $filter->createView()
        ]);
    }

}