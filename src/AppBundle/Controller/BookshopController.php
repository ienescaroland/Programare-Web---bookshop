<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\User;
use AppBundle\Entity\BookRequest;
use AppBundle\Entity\PBooks;
use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use blackknight467\StarRatingBundle\Form\RatingType as RatingType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $queryBuilder = $em->getRepository('AppBundle:Book')->createQueryBuilder('b');

        if ($request->query->getAlnum('filterbook')) {
            $queryBuilder->where('b.title LIKE :title')
                ->setParameter('title', '%' . $request->query->getAlnum('filterbook') . '%');
        }

        if ($request->query->getAlnum('filterauthor')) {
            $queryBuilder->where('b.author LIKE :author')
                ->setParameter('author', '%' . $request->query->getAlnum('filterauthor') . '%');
        }

        $query = $queryBuilder->getQuery();

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
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('author', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('price', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('stock', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('submit', SubmitType::class, array('label' => 'Add Book', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $author = $form['author']->getData();
            $price = $form['price']->getData();
            $stock = $form['stock']->getData();

            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setPrice($price);
            $book->setStock($stock);

            $em = $this->getDoctrine()->getManager();

            $em->persist($book);
            $em->flush();

            $this->addFlash(
                'notice',
                'Book added.'
            );
            return $this->redirectToRoute('bookshop_books');
        }

        return $this->render('bookshop/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/requestbook", name="bookshop_request")
     */
    public function requestAction(Request $request)
    {
        $bRequest = new BookRequest;
        $form = $this->createFormBuilder($bRequest)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('author', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('submit', SubmitType::class, array('label' => 'Request', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $author = $form['author']->getData();
            $user = $this->getUser();
            $username = $user->getUsername();
            $approved = false;

            $bRequest->setTitle($title);
            $bRequest->setAuthor($author);
            $bRequest->setUsername($username);
            $bRequest->setApproved($approved);

            $em = $this->getDoctrine()->getManager();
            $em->persist($bRequest);
            $em->flush();

            $this->addFlash(
                'notice',
                'Request created.'
            );

            return $this->redirectToRoute('bookshop_request');
        }

        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT r FROM AppBundle:BookRequest r";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)
        );

        return $this->render('bookshop/bookrequest.html.twig', array(
            'book_request' => $result,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/details/{id}", name="bookshop_details")
     */
    public function detailsAction($id, Request $request)
    {
        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->find($id);

        $comment = new Comment;
        $form = $this->createFormBuilder($comment)
            //->add('rating', RatingType::class, array('label' => 'Rating'))
            ->add('message', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:700px')))
            ->add('submit', SubmitType::class, array('label' => 'Reply', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user_id = $user->getId();
            $book_id = $id;
            $message = $form['message']->getData();

            $comment->setUserId($user_id);
            $comment->setBookId($book_id);
            $comment->setMessage($message);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'notice',
                'Comment created.'
            );

            return $this->redirect($request->getUri());
            //return $this->redirectToRoute('bookshop_details', array('id' => $id));
        }

        $comments = $this->getDoctrine()
            ->getRepository('AppBundle:Comment')->findBy(
                array('bookId' => $id)
            );

        $bcomments = array();
        foreach ($comments as $comment) {
            $commentId = $comment->getId();
            $userId = $comment->getUserId();
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->find($userId);
            $username = $user->getUsername();
            $message = $comment->getMessage();
            $bcomments[] = array($username, $message, $commentId);
        }

        return $this->render('bookshop/details.html.twig', array(
            'book' => $book,
            'bcomments' => $bcomments,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/deletecomment/{id}/{bookId}", name="bookshop_delete_comment")
     */
    public function deleteCommentAction($id, $bookId)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:Comment')->find($id);

        if($comment) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash(
                'notice',
                'Comment removed.'
            );

            return $this->redirectToRoute('bookshop_details', array('id' => $bookId)); 
        }

        return $this->redirectToRoute('bookshop_books');
    }

    /**
     * @Route("/buy/{id}", name="bookshop_buy")
     */
    public function buyAction($id)
    {
        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->find($id);

        $user_id = $this->getUser()->getId();
        $book_id = $book->getId();

        $pBook = $this->getDoctrine()
            ->getRepository('AppBundle:PBooks')
            ->findOneBy(
                array('idBook' => $book_id, 'idUser' => $user_id)
            );

        if($pBook) {
            $this->addFlash(
                'danger',
                'You have already purchased this book.'
            );

            return $this->redirectToRoute('bookshop_books');
        }
        else {
            $purchasedBook = new PBooks;
            $purchasedBook->setIdBook($book_id);
            $purchasedBook->setIdUser($user_id);
            $book->setStock($book->getStock()-1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($purchasedBook);
            $em->flush();

            $this->addFlash(
                'notice',
                'Book purchased.'
            );

            return $this->redirectToRoute('bookshop_books');
        }
    }

    /**
     * @Route("/profile/{id}", name="bookshop_profile")
     */
    public function profileAction($id, Request $request)
    {   
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:PBooks');
        $userRepository = $repository->findBy(
            array('idUser' => $id)
        );

        $ubooks = array();
        foreach ($userRepository as $book) {
            $bookId = $book->getIdBook();
            $ubook = $this->getDoctrine()
                ->getRepository('AppBundle:Book')
                ->find($bookId);
            if($ubook) {
                $ubooks[] = $ubook;
            } 
        }

        return $this->render('bookshop/profile.html.twig', array(
            'ubooks' => $ubooks
        ));
    }

    /**
     * @Route("/approverequest/{id}", name="bookshop_approve")
     */
    public function approveAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bRequest = $em->getRepository('AppBundle:BookRequest')->find($id);
        $titleRequest = $bRequest->getTitle();
        $bookCheck = $em->getRepository('AppBundle:Book')->findOneBy(
            array('title' => $titleRequest)
        );

        if($bRequest->getApproved()) {
            $this->addFlash(
                'danger',
                'Request already approved.'
            );

            return $this->redirectToRoute('bookshop_request');
        }
        else {
            if (!$bookCheck) {
                $book = new Book;
                $book->setTitle($bRequest->getTitle());
                $book->setAuthor($bRequest->getAuthor());
                $book->setPrice(20);
                $book->setStock(10);
                $approve = true;
                $bRequest->setApproved($approve);

                $em->persist($book);
                $em->flush();

                $this->addFlash(
                    'notice',
                    'Request approved and book added to database.'
                );

                return $this->redirectToRoute('bookshop_request');
            }
            else {
                $this->addFlash(
                    'danger',
                    'Request not approved, book already exists in database.'
                );

                return $this->redirectToRoute('bookshop_request');
            }
        }
    }

    /**
     * @Route("/edit/{id}", name="bookshop_edit")
     */
    public function editAction($id, Request $request)
    {
        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->find($id);

        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('author', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('price', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('stock', NumberType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px; width:420px')))
            ->add('submit', SubmitType::class, array('label' => 'Edit Book', 'attr' => array('class' => 'btn btn-success', 'style' => 'margin-bottom:15px')))
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $title = $form['title']->getData();
            $author = $form['author']->getData();
            $price = $form['price']->getData();
            $stock = $form['stock']->getData();

            $em = $this->getDoctrine()->getManager();

            $book = $em->getRepository('AppBundle:Book')->find($id);
            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setPrice($price);
            $book->setStock($stock);

            $em->flush();

            $this->addFlash(
                'notice',
                'Book updated.'
            );

            return $this->redirectToRoute('bookshop_books');
        }

        return $this->render('bookshop/edit.html.twig', array(
            'book' => $book,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="bookshop_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->find($id);

        $em->remove($book);
        $em->flush();
        $this->addFlash(
            'notice',
            'Book removed.'
        );

        return $this->redirectToRoute('bookshop_books');
    }

    /**
     * @Route("/deleterequest/{id}", name="bookshop_delete_request")
     */
    public function deleteRequestAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $bRequest = $em->getRepository('AppBundle:BookRequest')->find($id);

        $em->remove($bRequest);
        $em->flush();
        $this->addFlash(
            'notice',
            'Request removed.'
        );

        return $this->redirectToRoute('bookshop_request');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('bookshop/login.html.twig', array(
            'errors' => $errors,
            'username' => $lastUserName,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //create user
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('bookshop/register.html.twig', array(
            'form' => $form->createView()
        ));
    }
}