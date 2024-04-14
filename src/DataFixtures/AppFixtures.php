<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Skill;
use App\Entity\Occupation;
use App\Repository\OccupationRepository;
use App\Repository\SkillRepository;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** OccupationRepository $occupationRepository */
        $occupationRepository = $manager->getRepository(Occupation::class);

        /** SkillRepository $skillRepository */
        $skillRepository = $manager->getRepository(Skill::class);
        $skills = [
            ['name' => 'maçonnerie', 'description' => 'construire des murs de batiments'],
            ['name' => 'diagnostique médicale', 'description' => "identifier les maladies en auscultant les malades"],
            ['name' => 'soins médiacux', 'description' => "administrer des soins médicaux"],
            ['name' => 'agriculture', 'description' => "cultiver la terre et d'élèver des bêtes"],
            ['name' => 'conduite de vehicule', 'description' => "conduire des voitures, camions ou engins"],
            ['name' => 'mecanique', 'description' => "réparer les moteurs"],
            ['name' => 'ingenierie', 'description' => "concevoir des machines complexes"],
            ['name' => 'peinture', 'description' => "peindre des murs"],
        ];
        foreach($skills as $i => $skill) {
            ${'skill' . $i}  = $skillRepository->findOneBy(['name' => $skill['name']]);
            if (empty(${'skill' . $i})) {
                ${'skill' . $i} = new Skill();
                $manager->persist(${'skill' . $i});
            }
            ${'skill' . $i}->setName($skill['name']);
            ${'skill' . $i}->setDescription($skill['description']);
            
        }
        
        $occupations = [
            ['name'=> 'ouvrier du bâtiment', 'skills' => [0, 7]],
            ['name'=> 'médecin', 'skills' => [1, 2]],
            ['name'=> 'agriculteur', 'skills' => [3]],
            ['name'=> 'ingénieur', 'skills' => [6]],
            ['name'=> 'Chauffeur', 'skills' => [4]],
            ['name'=> 'Mécanicien', 'skills' => [4, 5]],
            ['name'=> 'Infirmier', 'skills' => [2]]
        ];

        foreach($occupations as $j => $occupation) {
            /** Occupation */
            ${'occupation' . $j} = $occupationRepository->findOneBy(['name' => $occupation['name']]);
            if (empty(${'occupation' . $j})) {
                ${'occupation' . $j} = new Occupation();
                $manager->persist(${'occupation' . $j});
            }
            ${'occupation' . $j}->setName($occupation['name']);
            foreach ($occupation['skills'] as $skillNum) {
                ${'occupation' . $j}->addSkill( ${'skill' . $skillNum});
            }
        }

        $manager->flush();
    }
}
