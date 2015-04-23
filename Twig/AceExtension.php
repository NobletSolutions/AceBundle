<?php

namespace NS\AceBundle\Twig;

class AceExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_horizontal', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html')))
        );
    }

    public function getName()
    {
        return 'ace_extension';
    }
}