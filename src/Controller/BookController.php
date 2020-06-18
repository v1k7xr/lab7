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
    public function changeBook(Request $request, $id) {

        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('mainpage');
        }

        $path = ".jpg";

        $request = $this->get('request_stack')->getCurrentRequest();
        

        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        $imageLocation = $book->getImageLocation();
        $bookLocation = $book->getBookLocation();

        $base64S = $this->getParameter('image_storage_dir') . $imageLocation . $path;

        if (file_exists($base64S)) {
            $data = base64_encode(file_get_contents($base64S));
        } else {
            $data = "";
        }
        $book->setImageLocation(($data));

        $form = $this->createForm(ChangeBookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $book->setImageLocation($imageLocation);
            $book->setBookLocation($bookLocation);
            $book->setId($id);
            // perform some action, such as save the object to the database
            $entityManager->flush();

            return $this->redirectToRoute('mainpage');
        }

        return $this->render('book/change.html.twig', [
            'book' => $book,
            'changeBook' => $form->createView(),
        ]);
        
    }

    /**
    * @Route("/book/deletebook/{id}", name="book_deleteBook")
    */
    public function deleteBook($id) {

        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('mainpage');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        $ext = BookController::getFileExtension($book->getBookLocation(), $this->getParameter('book_storage_dir'));

        if ($ext) {
            $bookLocation = $this->getParameter('book_storage_dir') . $book->getBookLocation() . "." . $ext;
        }
        else {
            return $this->redirectToRoute('mainpage');
        }
        
        unlink($bookLocation);

        $book->setBookLocation("");
        $book->setId($id);

        $entityManager->flush();

        return $this->redirectToRoute('book_changeBook', ['id'=>$id]);
        
    }

    /**
    * @Route("/book/deleteimg/{id}", name="book_deleteImage")
    */
    public function deleteImage($id) {

        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('mainpage');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        $path = ".jpg";

        $imageLocation = $this->getParameter('image_storage_dir') . $book->getImageLocation() . $path;

        unlink($imageLocation);

        $book->setImageLocation("");
        $book->setId($id);

        $entityManager->flush();

        return $this->redirectToRoute('book_changeBook', ['id'=>$id]);
    }

    /**
    * @Route("/book/deleterecord/{id}", name="book_deleteRecord")
    */
    public function deleteRecord($id) {

        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('mainpage');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        $path = ".jpg";

        $imageLocation = $this->getParameter('image_storage_dir') . $book->getImageLocation() . $path;

        $ext = BookController::getFileExtension($book->getBookLocation(), $this->getParameter('book_storage_dir'));

        if ($ext) {
            $bookLocation = $this->getParameter('book_storage_dir') . $book->getBookLocation() . "." . $ext;
            unlink($bookLocation);
        }

        if ($book->getImageLocation() != '') {
            unlink($imageLocation);   
        }

        $book->setId($id);

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('mainpage');
    }

    /**
    * @Route("/adding", name="book_addBook")
    */
    public function addBook(Request $request) {

        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('mainpage');
        }

        $book = new Book();

        $form = $this->createForm(AddBookType::class, $book)->handleRequest($request);

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

    public static function  getFileExtension($name, $path='')
    {
        $path = $path ? $path . '/' : '';
        $arr_files = scandir($path);
        foreach ($arr_files as $file)
        {
            $file_info = pathinfo($file);
            if ($name == $file_info['filename'])
            {
                return $file_info['extension'];
            }
        }
        return FALSE;
    }

}
