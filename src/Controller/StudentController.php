<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /*
    /**
     * @Route("/student", name="student")
     */
    /*public function index()
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    */
    /**
     * @Route("/student/{id}", name="student_show")
     */
    public function showAction(Student $student)
    {


        $template = 'student/show.html.twig';
        $args = [
          'student' => $student
        ];

        if (!$student){
            $template = 'error/404.html.twig';
        }
        return $this->render($template, $args);
    }

    /**
     * @Route("/student/list", name="student_list")
     */
    public function listAction(Student $student)
    {
        $studentRepository = new StudentRepository();
        $students = $studentRepository->findAll();

        $template = 'student/list.html.twig';
        $args = [
            'students' => $students
        ];
        return $this->render($template,$args);
    }
    /**
     *@Route("/student/create/{firstName}/{surname}")
     */

    public function createAction($firstName, $surname){

        $student = new Student();
        $student->setFirstName($firstName);
        $student->setSurname($surname);

        //entity manager
        $em = $this->getDoctrine()->getManager();

        //tells Doctrine you want to save the Produce
        $em->persist($student);

        //actually executes the queries (i.e the INSERT query)
        $em->flush();

        return new Response('Created new student with id '.$student->getId());

    }

    /**
     * @param Student $student
     * @return Response
     * @Route ("/student/delete/{id}")
     */

    public function deleteAction(Student $student)
    {
        //call the entity manager
        $em = $this->getDoctrine()->getManager();

        //store the IDe before eleting so you can report ID later
        $id = $student->getId();

        //tells Doctrine you want to eventually delete the student so no queries yet
        $em->remove($student);



        $em->flush();

        return new Response('Deleted student with id = ' .$id);
        }//end of deleteAction

    /**
     * @param Student $student
     * @param $newFirstName
     * @param $newSurname
     * @Route("/student/update/{id}/{newFirstName}/{newSurname}")
     */

    public function updateAction(Student $student, $newFirstName, $newSurname){


        //call the entity manager
        $em = $this->getDoctrine()->getManager();

        $student->setFirstName($newFirstName);

        $student->setSurname($newSurname);


        $em->flush();

        //return new Response('Deleted student with id = ' .$id);

        return $this->redirectToRoute('student_show', [
            'id' => $student->getId()
        ]);

        }//end of UpdateAction
    }