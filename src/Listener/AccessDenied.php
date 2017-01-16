<?php

namespace NS\AceBundle\Listener;

use \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccessDenied
 *
 * @author gnat
 */
class AccessDenied implements AccessDeniedHandlerInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     *
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return \Symfony\Component\HttpFoundation\Response
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new Response("Access Denied!");
    }
}
