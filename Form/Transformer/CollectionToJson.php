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
     *
     * @todo allow configuration of the entity toString method
     */
    public function transform($entities)
    {
        if (null === $entities || empty($entities))
            return "";

        if (!$entities instanceof Collection && !is_array($entities))
            throw new UnexpectedTypeException($entities, 'PersistentCollection or ArrayCollection');

        $idsArray = array();
        // check for interface...
        foreach ($entities as $entity)
            $idsArray[] = array('id' => $entity->getId(), 'name' => $entity->__toString());

        if (empty($idsArray))
            return null;

        return json_encode($idsArray);
    }

    /**
     * Transforms an json string to an entity
     *
     * @param  string|null $jsonStr
     * @return Entity
     * @throws UnexpectedTypeException
     */
    public function reverseTransform($jsonStr)
    {
        if ('' === $jsonStr || null === $jsonStr)
            return new ArrayCollection();

        if (!is_string($jsonStr))
            throw new UnexpectedTypeException($jsonStr, 'string');

        $idsArray = json_decode($jsonStr, true);

        if (empty($idsArray))
            return new ArrayCollection();

        array_walk($idsArray, array($this, 'walk'));

        return $idsArray;
    }
}