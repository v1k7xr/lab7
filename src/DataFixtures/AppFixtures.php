<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->passwordEncoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setUsername('booklover');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'Qwerty123!'));
        $user->setRoles(['ROLE_ADMIN']);

        $book1 = new Book();
        $book1->setNameBook("simple script");
        $book1->setAuthorBook("me");
        $book1->setImageLocation("b52e2bd6d906fb479877d22352da47ca");
        $book1->setBookLocation("7E766F4FCD50982CCBDDD3920F71E9D7");
        $book1->setReadingDate('2020-12-02');

        $book2 = new Book();
        $book2->setNameBook("UNIX History");
        $book2->setAuthorBook("Wiki");
        $book2->setImageLocation("b52e2bd6d906fb479877d22352da47ca");
        $book2->setBookLocation("2C84F92A672D769B2D8E0842CA6FEFA2");
        $book1->setReadingDate(new \DateTime("2020-12-01"));

        $manager->persist($user);
        $manager->persist($book1);
        $manager->persist($book2);

        $manager->flush();
    }
}
