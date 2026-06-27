<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class NameFormat extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\Regex(
                pattern: '/^\p{L}+([ \'’-]\p{L}+)*$/u',
                message: 'Ce champ ne peut contenir que des lettres, espaces, traits d\'union et apostrophes.',
            ),
        ];
    }
}
