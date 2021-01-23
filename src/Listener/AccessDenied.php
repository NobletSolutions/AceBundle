<?php

namespace NS\AceBundle\Listener;

use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AccessDenied implements AccessDeniedHandlerInterface
{
    /** @var Environment */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new Response("Access Denied!");
    }
}
