<?php

namespace NS\AceBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AceExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('form_horizontal', null, ['node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => ['html']])
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
