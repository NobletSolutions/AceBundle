<?php

namespace NS\AceBundle\Form\Transformer;

use \Symfony\Component\Form\DataTransformerInterface;

/**
 * Description of EntityOrCreateTransformer
 *
 * @author gnat
 */
class EntityOrCreate implements DataTransformerInterface
{

    /**
     * This takes the submitted values and determines which to submit
     * 
     * @param type $value
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!empty($value['finder']) && is_object($value['finder'])) {
            return $value['finder'];
        }
        elseif (!empty($value['createForm'])) {
            return $value['createForm'];
        }

        return null;
    }

    /**
     * 
     * @param type $value
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return $value;
        }
    }
}