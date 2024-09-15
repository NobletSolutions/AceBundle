<?php

namespace NS\AceBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityOrCreate implements DataTransformerInterface
{
    private bool $expectMultiple;

    private bool $expectCreateForm;

    private string $class;

    public function __construct(bool $expectMultiple, bool $expectCreateForm, string $class)
    {
        $this->expectMultiple   = $expectMultiple;
        $this->expectCreateForm = $expectCreateForm;
        $this->class            = $class;
    }

    public function reverseTransform($value): ?array
    {
        if ($this->expectCreateForm) {
            if (!isset($value['createFormClicked'])) {
                throw new TransformationFailedException("Unable to reverseTransform as we expected the createFormClicked but it wasn't submitted");
            }

            return match ($value['createFormClicked']) {
                'finder' => $this->handleFinder($value),
                'create' => $this->handleCreate($value),
                default => throw new TransformationFailedException("CreateFormClicked invalid"),
            };
        }

        return $this->handleFinder($value);
    }

    private function handleFinder(array $value): ?array
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

    private function handleCreate(array $value): ?array
    {
        if (!isset($value['createForm'])) {
            throw new TransformationFailedException("Missing 'createForm' array key");
        }

        if (!empty($value['createForm']) && $value['createForm'] instanceof $this->class) {
            return ($this->expectMultiple) ? [$value['createForm']] : $value['createForm'];
        }

        return null;
    }

    public function transform(mixed $value): mixed
    {
        if ($value === null) {
            return $value;
        }

        return null;
    }
}
