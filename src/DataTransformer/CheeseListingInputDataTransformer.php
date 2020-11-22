<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\CheeseListingInput;
use App\Entity\CheeseListing;

class CheeseListingInputDataTransformer implements DataTransformerInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {

        $this->validator = $validator;
    }

    /**
     * @param CheeseListingInput $input
     * @param string $to
     * @param array $context
     * @return CheeseListing|object
     */
    public function transform($input, string $to, array $context = [])
    {
        // vendor/api-platform/core/src/Bridge/Symfony/Validator/Validator.php
        $this->validator->validate($input);

        $cheeseListing = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;

        return $input->createOrUpdateEntity($cheeseListing);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof CheeseListing) {
            // si l'objet est déjà transformé alors on sort
            return false;
        }

        return $to === CheeseListing::class && ($context['input']['class'] ?? null) === CheeseListingInput::class;
    }
}
