<?php

namespace App\DataFixtures;

use App\Entity\Comic;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\NewComicAd;
use App\Entity\Role;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this -> encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser  ->setFirstname('Nounou')
                    ->setLastname('Kun')
                    ->setEmail('nounoukun@gmail.com')
                    ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                    ->setPicture('https://avatars.io/twitter/Nounou')
                    ->addUserRole($adminRole);

        $manager->persist($adminUser);


//        $slugify = new Slugify();

        //Sert à gérer les fixtures des users
//        $users = [];
        $genders = ['male', 'female'];

        for ($i = 0; $i <= 9; $i++){
            $user = new User();

            $gender = $faker->randomElement($genders);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';

            //la méthode encodePassword prend 2 paramètres, l'entité sur laquelle je veux opérer et le mdp à hasher
            $hash = $this->encoder->encodePassword($user, 'password');


            if($gender == "male"){
                $picture = $picture . 'men/' . $pictureId;
            }else {
                $picture = $picture . 'women/' . $pictureId;
            }

            $user -> setFirstname($faker->firstname($gender))
                  -> setLastname($faker->lastname)
                  -> setEmail($faker->email)
                  -> setHash($hash)
                  -> setPicture($picture);

            $manager->persist($user);
//            $users[] = $user;
        }

        //Sert à gérer les fixtures des comics
        for ($i = 0; $i <= 14; $i++){
            $comic = new Comic();

            $title = $faker -> sentence(3);
//            $slug = $slugify->slugify($title);
            $coverImage = ("https://loremflickr.com/250/370/cat?lock=".$i);
            $about = $faker -> paragraph(1);
            $content = $faker -> paragraph(4);
//            $content = '<p>' . $faker -> paragraph(4) . '</p>';
            $scenario = $faker -> sentence(3);
            $draw = $faker -> sentence(3);
            $editor = $faker -> sentence(3);


            $comic -> setTitle($title)
//                -> setSlug($slug)
                -> setCoverImage($coverImage)
                -> setAbout($about)
                -> setContent($content)
                -> setPrice(mt_rand(5, 9))
                -> setQuantity(mt_rand(0, 12))
                -> setScenario($scenario)
                -> setDraw($draw)
                -> setEditor($editor);


            for ($j = 0; $j <= 3; $j++){
                $image = new Image();

                $image -> setUrl("https://loremflickr.com/250/370/cat?lock=".$i)
                       -> setCaption($faker -> sentence())
                       -> setComic($comic);

                $manager -> persist($image);
            }
            

            $manager -> persist($comic);

            // Gestion des commentaires
//            if (mt_rand(0,1)){
//                $comment = new Comment();
//
//                $comment->setContent($faker->paragraph())
//                        ->setRating(mt_rand(1,5))
//                        ->setAuthor($user)
//                        ->setComic($comic);
//
//                $manager->persist($comment);
//            }
        }



        $manager -> flush(); //flush envoi la requête
    }
}
