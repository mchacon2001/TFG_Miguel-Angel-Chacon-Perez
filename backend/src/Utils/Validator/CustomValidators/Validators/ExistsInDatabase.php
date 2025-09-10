<?php

namespace App\Utils\Validator\CustomValidators\Validators;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsInDatabase extends ConstraintValidator
{
    public function __construct(public ?EntityManagerInterface $em = null)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if($constraint->optional && !$value) {
            return;
        }

        if (!$value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', "Not value received")
                ->addViolation();
            return;
        }

        if (!$this->em) {
            throw new \Exception('EntityManager is required');
        }

        $entity = $this->em->getRepository($constraint->entityClass)->findOneBy([$constraint->field => $value]);

        if (!$entity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}