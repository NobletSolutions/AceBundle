<?php

namespace NS\AceBundle\Service;

use \JMS\Serializer\SerializationContext;
use \JMS\Serializer\SerializerInterface;
use NS\AceBundle\Ajax\Fields;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

/**
 * Description of JsonAutocompleter
 *
 * @author gnat
 */
class JsonAutocompleter
{
    private $serializer;

    private $context;

    /**
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     *
     * @return SerializationContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     *
     * @param SerializationContext $context
     * @return \NS\AceBundle\Service\JsonAutocompleter
     */
    public function setContext(SerializationContext $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @param Request $request
     * @param EntityRepository $repository
     * @param string $method
     * @param integer $limit
     * @return Response
     */
    public function getResults(Request $request, $repository, $method, $limit = 20)
    {
        $fields = new Fields($request->request->get('q'), $request->request->get('secondary-field'));
        $data   = $repository->$method($fields, $limit);

        return new Response($this->serializer->serialize($data, 'json', $this->context));
    }
}
