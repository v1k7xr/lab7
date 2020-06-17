<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\AddBookType;
use App\Form\ChangeBookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book")
     */
    public function index()
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
    * @Route("/book/{id}", name="book_showOne")
    */
    public function showOne($id) {
        $book = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'Книги с id ' . $id . ' нет в библиотеке'
            );
        }

        return $this->render('book/index.html.twig', ['book' => $book]);
    }

    /**
    * @Route("/book/change/{id}", name="book_changeBook")
    */
    public function changeBook($id) {

        $book = $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);

        $path = ".jpg";

        $base64S = $this->getParameter('image_storage_dir') . $book->getImageLocation() . $path;

        $data = file_get_contents($base64S);
        $base64S = base64_encode($data);

        $book->setImageLocation($base64S);

        $form = $this->createForm(ChangeBookType::class, $book);

        return $this->render('book/change.html.twig', [
            'changeBook' => $form->createView(),
        ]);
    }

    /**
    * @Route("/adding", name="book_addBook")
    */
    public function addBook(Request $request) {

        $book = new Book();

        $form = $this->createForm(AddBookType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $fileImage = $book->getImageLocation();
            $fileBook = $book->getBookLocation();

            $serverFileNameImage = md5_file($fileImage);
            $serverFileNameBook = md5_file($fileBook);

            $book->setImageLocation($serverFileNameImage);
            $book->setBookLocation($serverFileNameBook);

            //$path = $_FILES['add_book']['name']['imageLocation'];
            //$extImage = pathinfo($path, PATHINFO_EXTENSION);

            $extImage = "jpg";

            $path = $_FILES['add_book']['name']['bookLocation'];
            $extBook = pathinfo($path, PATHINFO_EXTENSION);

            move_uploaded_file($fileImage, $this->getParameter('image_storage_dir') .  $serverFileNameImage . '.' . $extImage);
            move_uploaded_file($fileBook, $this->getParameter('book_storage_dir') .  $serverFileNameBook . '.' . $extBook);

            $entityManager->persist($book);

            $entityManager->flush();

            $lastQuestion = $this->getDoctrine()->getRepository(Book::class)->findOneBy([], ['id' => 'desc']);
            $lastId = $lastQuestion->getId();

            return $this->redirectToRoute('book_showOne', array('id' => $lastId));

        }

        return $this->render('book/adding.html.twig', [
            'addingBook' => $form->createView(),
        ]);
    }

}
