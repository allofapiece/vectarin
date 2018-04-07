<?php

namespace App\DataFixtures;

class AppFixtures extends \Doctrine\Bundle\FixturesBundle\Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $question=new \App\Entity\Question();
            $question->setText('qwerty');

            $answer=new \App\Entity\Answer();
            $answer->setText('yes');
            $answer->setIsCorrect('1');

            $question->addAnswer($answer);

            $manager->persist($question);
        }
        $manager->flush();
    }
}