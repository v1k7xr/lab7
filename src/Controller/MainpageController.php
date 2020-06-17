<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Kernel;

class MainpageController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index()
    {
        $tPathToFile = realpath(__DIR__ . "/../../public/assets/storage/image/");
        

        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findBy([],
            ['ReadingDate' => 'ASC']
        );
        foreach($books as $book) {
            $path = $tPathToFile . "/" . $book->getImageLocation() . ".jpg";
            $data = file_get_contents($path);
            $book->setImageLocation(base64_encode($data));
        }

        return $this->render('mainpage/index.html.twig', [
            'controller_name' => 'MainpageController',
            'books' => $books,
        ]);
    }
}
