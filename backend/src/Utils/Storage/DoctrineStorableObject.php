<?php
namespace App\Utils\Storage;

use Doctrine\ORM\EntityManagerInterface;

trait DoctrineStorableObject
{

    private function save(EntityManagerInterface $em, $entity)
    {

        $em->persist($entity);
        $em->flush();
    }

    private function delete(EntityManagerInterface $em, $entity)
    {
        $em->remove($entity);
        $em->flush();
    }
}