<?php

namespace NS\AceBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TextToArrayTransformer implements DataTransformerInterface
{
    public function reverseTransform($value): array
    {
        return explode(",", str_replace(", ", ",", $value));
    }

    public function transform($value): ?string
    {
        if (is_array($value) && !empty($value)) {
            return implode(",", $value);
        }

        return null;
    }
}
