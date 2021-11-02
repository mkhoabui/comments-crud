<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CommentRepository;
use App\Entity\Comment;
use App\Form\CommentType;

#[Route('/comments', name: 'comments.')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'all')]
    public function comments(CommentRepository $cRepository): Response
    {
        $comments = $cRepository->findAll();

        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function comments_create(Request $request): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'notice',
                'Comment added successfully!'
            );

            return $this->redirect($this->generateUrl('comments.all'));
        }

        return $this->render('comment/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
