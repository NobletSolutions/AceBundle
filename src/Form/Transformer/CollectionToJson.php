<?php

namespace NS\AceBundle\Form\Transformer;

use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Collections\Collection;
use \Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Description of CollectionToJson
 *
 * @author gnat
 */
class CollectionToJson extends AbstractObjectToJson
{
    /**
     * Transforms an object (issue) to a json {id: integer, string: string }
     *
     * @param  Entity|null $entities
     * @return string
     * @throws UnexpectedTypeException
     */
    public function transform($entities)
    {
        if (null === $entities || empty($entities)) {
            return "";
        }

        if (!$entities instanceof Collection && !is_array($entities)) {
            throw new UnexpectedTypeException($entities, 'PersistentCollection or ArrayCollection');
        }

        $idsArray = [];
        // check for interface...
        foreach ($entities as $entity) {
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
     * @param  string|null $ids
     * @return array
     * @throws UnexpectedTypeException
     */
    public function reverseTransform($ids)
    {
        if ('' === $ids || null === $ids || empty($ids)) {
            return [];
        }

        if (!is_string($ids)) {
            throw new UnexpectedTypeException($ids, 'string');
        }

        $idsArray = explode(',', $ids);

        if (empty($idsArray)) {
            return [];
        }

        array_walk($idsArray, [$this, 'walk']);

        return $idsArray;
    }
}
