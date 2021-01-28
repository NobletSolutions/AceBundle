<?php

namespace NS\AceBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityOrCreate implements DataTransformerInterface
{
    /** @var bool */
    private $expectMultiple;

    /** @var bool */
    private $expectCreateForm;

    /** @var string */
    private $class;

    public function __construct(bool $expectMultiple, bool $expectCreateForm, string $class)
    {
        $this->expectMultiple   = $expectMultiple;
        $this->expectCreateForm = $expectCreateForm;
        $this->class            = $class;
    }

    /**
     * This takes the submitted values and determines which to submit
     *
     * @param array $value
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($this->expectCreateForm) {
            if (!isset($value['createFormClicked'])) {
                throw new TransformationFailedException("Unable to reverseTransform as we expected the createFormClicked but it wasn't submitted");
            }

            switch ($value['createFormClicked']) {
                case 'finder':
                    return $this->handleFinder($value);
                case 'create':
                    return $this->handleCreate($value);
                default:
                    throw new TransformationFailedException("CreateFormClicked invalid");
            }
        }

        return $this->handleFinder($value);
    }

    /**
     * @param array $value
     *
     * @return array|mixed|null
     */
    private function handleFinder(array $value)
    {
        if (!isset($value['finder'])) {
            throw new TransformationFailedException("Missing 'finder' array key");
        }

        if ($this->expectMultiple) {
            if (is_array($value['finder']) && !empty($value['finder'])) {
                foreach ($value['finder'] as $subObject) {
                    if (!$subObject instanceof $this->class) {
                        throw new TransformationFailedException("Expected object of type {$this->class} got " . get_class($subObject));
                    }
                }

                return $value['finder'];
            }
        }

        if ($value['finder'] instanceof $this->class) {
            return $value['finder'];
        }

        return null;
    }

    /**
     * @param array $value
     *
     * @return array|mixed|null
     */
    private function handleCreate(array $value)
    {
        if (!isset($value['createForm'])) {
            throw new TransformationFailedException("Missing 'createForm' array key");
        }

        if (!empty($value['createForm']) && $value['createForm'] instanceof $this->class) {
            return ($this->expectMultiple) ? [$value['createForm']] : $value['createForm'];
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
