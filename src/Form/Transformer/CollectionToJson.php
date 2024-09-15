<?php

namespace NS\AceBundle\Form\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class CollectionToJson extends AbstractObjectToJson
{
    /**
     * Transforms an object (issue) to a json {id: integer, string: string }
     *
     * @param  Collection|array|null $value
     * @return string|false|null
     * @throws UnexpectedTypeException
     */
    public function transform($value): string|null|false
    {
        if (empty($value)) {
            return "";
        }

        if (!$value instanceof Collection && !is_array($value)) {
            throw new UnexpectedTypeException($value, 'PersistentCollection or ArrayCollection');
        }

        $idsArray = [];
        // check for interface...
        foreach ($value as $entity) {
            if ($entity !== null) {
                $idsArray[] = ['id' => $entity->getId(), 'name' => $this->getProperty($entity)];
            }
        }

        if (empty($idsArray)) {
            return null;
        }

        return json_encode($idsArray);
    }

    /**
     * Transforms an json string to an entity
     *
     * @param  string|null $value
     * @return array
     * @throws UnexpectedTypeException
     */
    public function reverseTransform($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $idsArray = explode(',', $value);

        if (empty($idsArray)) {
            return [];
        }

        array_walk($idsArray, [$this, 'walk']);

        return $idsArray;
    }
}
