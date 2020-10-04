<?php

namespace App\Validator;

use App\Entity\CheeseListing;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidIsPublishedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\ValidIsPublished */

        if (!$value instanceof CheeseListing) {
            throw new \Exception('Only CheeseListing is supported');
        }

        dd($value);

        if (null === $value || '' === $value) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
