<?php

namespace NS\AceBundle\Form\Transformer;

/**
 * Description of EntityToJson
 *
 * @author gnat
 */
class EntityToJson extends AbstractObjectToJson
{
    /**
     * Transforms an object (issue) to a json {"id": integer, "name": "string" }
     *
     * @todo allow configuration of the entity toString method
     *
     * @param  Entity|null $entity
     * @return string
     */
    public function transform($entity)
    {
        if ($entity === null)
            return null;

        if (!$entity instanceof $this->class)
            throw new \InvalidArgumentException(sprintf("Expecting entity of type '%s' but received '%s'", $this->class, get_class($entity)));

        return json_encode(array('id' => $entity->getId(), 'name' => $entity->__toString()));
    }

    /**
     * Transforms an json string to an entity
     *
     * @param  string|null $jsonStr
     * @return Entity
     */
    public function reverseTransform($jsonStr)
    {
        if ($jsonStr === null)
            return null;

        $obj = json_decode($jsonStr, true);

        return $this->getReference($obj['id']);
    }
}