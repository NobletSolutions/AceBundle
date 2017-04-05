<?php

namespace NS\AceBundle\Form\Transformer;

use \Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Description of EntityOrCreateTransformer
 *
 * @author gnat
 */
class EntityOrCreate implements DataTransformerInterface
{
    /** @var bool */
    private $expectMultiple;

    /** @var string */
    private $class;

    /**
     * EntityOrCreate constructor.
     * @param bool $expectMultiple
     * @param string $class
     */
    public function __construct($expectMultiple, $class)
    {
        $this->expectMultiple = $expectMultiple;
        $this->class = $class;
    }

    /**
     * This takes the submitted values and determines which to submit
     *
     * @param array $value
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($this->expectMultiple) {
            if (is_array($value['finder']) && !empty($value['finder'])) {
                foreach ($value['finder'] as $subObject) {
                    if (!$subObject instanceof $this->class) {
                        throw new TransformationFailedException("Expected object of type {$this->class} got " . get_class($subObject));
                    }
                }

                return $value['finder'];
            }

            if (!empty($value['createForm']) && $value['createForm'] instanceof $this->class) {
                return [$value['createForm']];
            }

        } elseif (!$this->expectMultiple) {
            if ($value['finder'] instanceof $this->class) {
                return $value['finder'];
            }

            if (!empty($value['createForm']) && $value['createForm'] instanceof $this->class) {
                return $value['createForm'];
            }
        }

        return null;
    }

    /**
     *
     * @param mixed $value
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return $value;
        }
    }
}
