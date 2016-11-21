<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wanup\CmsBundle\Entity\Category;
use Wanup\CmsBundle\Entity\Post;
use Wanup\CmsBundle\Entity\Tag;

class PopulateDbCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('wanup:populate')
            ->setDescription('populate database')
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info>command creates a new client.
                    <info>php %command.full_name% [--redirect-uri=...] [--grant-type=...] name</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        $faker = Factory::create();

        $categoriesString = ['general', 'hotels', 'services'];
        $tagsString = ['fitness', 'no smoking', 'beach', 'breakfast', 'parking', 'airport',
            'internet', 'pets_allowed', 'accessible'];

        $tagCollection = [];
        foreach ($tagsString as $tagString) {
            $tag = new Tag();
            $tag->setName($tagString);
            $em->persist($tag);
            $tagCollection[] = $tag;
        }
        $em->flush();



        foreach ($categoriesString as $cat) {
            $c = new Category();
            $c->setName($cat);
            $em->persist($c);
            $em->flush();

            foreach (range(1, 1000) as $postIndex) {
                $post = new Post();
                $post->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
                $post->setBody($faker->text($maxNbChars = 200));
                $post->setCategory($c);
                foreach ($tagCollection as $tagItem) {
                    $post->addTag($tagItem);
                }
                $em->persist($post);
            }
            $em->flush();
        }

        $output->writeln("la la la");
    }
}