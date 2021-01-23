<?php

namespace NS\AceBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class TextToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms a string into an array
     *
     * @param string $value
     * @return array
     */
    public function reverseTransform($value)
    {
        return explode(",", str_replace(", ", ",", $value));
    }

    /**
     * Transforms an array to a comma separated string
     *
     * @param array $value
     * @return string
     */
    public function transform($value)
    {
        if (is_array($value) && !empty($value)) {
            return implode(",", $value);
        }

        return null;
    }
}
