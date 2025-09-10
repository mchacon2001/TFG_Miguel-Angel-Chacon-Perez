<?php
namespace App\Utils\Validator\CustomValidators\Constraints;
use App\Utils\Validator\CustomValidators\Validators\ExistsInDatabase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ConstraintExistsInDatabase extends Constraint
{
    public function __construct(
        public string $field,
        public string $entityClass,
        public ?string $message = null,
        public bool $optional = false,
        array $groups = null,
        mixed $payload = null
    )
    {
        $this->message = $this->message ?? 'The value {{ value }} is not valid for field {{ field }}.';
        parent::__construct([], $groups, $payload);
    }

    public function validatedBy()
    {
        return ExistsInDatabase::class;
    }
}