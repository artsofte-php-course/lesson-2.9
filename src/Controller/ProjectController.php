<?php 

namespace App\Controller;

use App\Entity\Project;
use App\Service\Slugify;
use App\Type\ProjectType;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends AbstractController {


    public function list(ManagerRegistry $doctrine) : Response
    {

        $projects = $doctrine->getRepository(Project::class)
                                ->findAll();

        return $this->render('list.html.twig', [
            'projects' => $projects
        ]);
    }

    public function add(ManagerRegistry $doctrine, Request  $request, Slugify $slugify) : Response 
    {
        $projectNum = rand(1,100);
        $projectName = sprintf('Project #%d', $projectNum);
        $projectSlug = $slugify->generateSlug($projectName);
        $project = new Project($projectName, $projectSlug);   


        $form = $this->createForm(ProjectType::class, $project);
        
        $form->handleRequest($request);    

        if ($form->isSubmitted() && $form->isValid()) {

            $project = $form->getData();
            $project->setUser($this->getUser()->getUserIdentifier());
            
            $doctrine->getManager()->persist($project);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('index');

        }


        return $this->render('projects/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function update(ManagerRegistry $doctrine, Request $request, $id)
    {
        $project = $doctrine->getRepository(Project::class)->find($id);
        
        if ($project === null) {
            return $this->createNotFoundException(
                sprintf("Project with id %d not found", $id)
            );
        }

        $form = $this->createForm(ProjectType::class, $project, [
            'isNew' => false, 
            'slug' => $project->getSlug()
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $doctrine->getManager()->persist($project);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('projects/edit.html.twig', [
            'form' => $form->createView()
        ]);

        
    }

    public function showById(ManagerRegistry $doctrine, int $id, string $key) : Response
    {   

        /** Project $project */
        $project = $doctrine->getRepository(Project::class)
            ->find($id);

        if ($project === null || $project->getSlug() !== $key) {
            
            return $this->redirect(
                $this->generateUrl('index')
            );

        }

        return $this->render('show.html.twig', [
            'id' => $id,
            'project' => $project
        ]);
    }

}