<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookshopController extends Controller
{
    /**
     * @Route("/", name="bookshop_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('bookshop/index.html.twig');
    }

     /**
     * @Route("/books", name="bookshop_books")
     */
    public function booksAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $books = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->findAll();
        $dql = "SELECT b FROM AppBundle:Book b";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('bookshop/books.html.twig', array(
            'books' => $result
        ));
    }

    /**
     * @Route("/add", name="bookshop_add")
     */
    public function addAction(Request $request)
    {
        $book = new Book;
        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('author', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('price', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('submit', SubmitType::class, array('label' => 'Add Book', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $author = $form['author']->getData();
            $price = $form['price']->getData();

            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setPrice($price);

            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();

            $this->addFlash(
                'notice',
                'Book Added'
            );
            return $this->redirectToRoute('bookshop_books');
        }

        return $this->render('bookshop/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/bookshop/details/{id}", name="bookshop_details")
     */
    public function detailsAction($id)
    {
        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->find($id);

        return $this->render('bookshop/details.html.twig', array(
            'book' => $book
        ));
    }

    /**
     * @Route("/bookshop/edit/{id}", name="bookshop_edit")
     */
    public function editAction($id, Request $request)
    {
        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->find($id);

        $book->setTitle($book->getTitle());
        $book->setAuthor($book->getAuthor());
        $book->setPrice($book->getPrice());

        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('author', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('price', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('submit', SubmitType::class, array('label' => 'Edit Book', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $author = $form['author']->getData();
            $price = $form['price']->getData();

            $em = $this->getDoctrine()->getManager();
            $book = $em->getRepository('AppBundle:Book')->find($id);
            
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setPrice($price);

            $em->flush();

            $this->addFlash(
                'notice',
                'Book Updated'
            );
            return $this->redirectToRoute('bookshop_books');
        }

        return $this->render('bookshop/edit.html.twig', array(
            'book' => $book,
            'form' => $form->createView()
        ));
    }

     /**
     * @Route("/bookshop/delete/{id}", name="bookshop_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        $em->remove($book);
        $em->flush();
        $this->addFlash(
            'notice',
            'Book Removed'
        );

        return $this->redirectToRoute('bookshop_books');
    }
}