<?php

namespace NS\AceBundle\Form\Transformer;

class EntityToJson extends AbstractObjectToJson
{
    /**
     * Transforms an object (issue) to a json {"id": integer, "name": "string" }
     *
     * @param  object|null $entity
     * @return string
     */
    public function transform($entity): mixed
    {
        if ($entity === null) {
            return null;
        }

        if (!$entity instanceof $this->class) {
            throw new \InvalidArgumentException(sprintf("Expecting entity of type '%s' but received '%s'", $this->class, get_class($entity)));
        }

        return json_encode([['id' => $entity->getId(), 'name' => $this->getProperty($entity)]]);
    }

    /**
     * Transforms an json string to an entity
     *
     * @param  string|null $id
     * @return object
     */
    public function reverseTransform($id): mixed
    {
        if ($id === null || empty($id)) {
            return null;
        }

        return $this->getReference($id);
    }
}
