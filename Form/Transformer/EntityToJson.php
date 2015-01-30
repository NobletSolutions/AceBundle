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
     * @param  Entity|null $entity
     * @return string
     */
    public function transform($entity)
    {
        if ($entity === null)
            return null;

        if (!$entity instanceof $this->class)
            throw new \InvalidArgumentException(sprintf("Expecting entity of type '%s' but received '%s'", $this->class, get_class($entity)));

        return json_encode(array(array('id' => $entity->getId(), 'name' => $this->getProperty($entity))));
    }

    /**
     * Transforms an json string to an entity
     *
     * @param  string|null $id
     * @return Entity
     */
    public function reverseTransform($id)
    {
        if ($id === null)
            return null;

        return $this->getReference($id);
    }
}