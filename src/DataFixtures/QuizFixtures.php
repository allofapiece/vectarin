<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 1:03
 */

namespace App\DataFixtures;


use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuizFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {


            $quiz = new Quiz();
            $quiz->setName('Викторина № '.$i);
            $quiz->setDescription('Очеень длинное описание для викторины №'.$i);
            //$quiz->addQuestion(new Question());

            $manager->persist($quiz);
        }

        $manager->flush();
    }
}