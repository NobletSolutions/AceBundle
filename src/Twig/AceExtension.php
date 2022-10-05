<?php

namespace NS\AceBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AceExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('form_horizontal', null, ['node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => ['html']])
        ];
    }

    public function getName(): string
    {
        return 'ace_extension';
    }
}
