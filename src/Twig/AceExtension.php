<?php

namespace NS\AceBundle\Twig;

class AceExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('form_horizontal', null, ['node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => ['html']])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ace_extension';
    }
}
