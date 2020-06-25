<?php

namespace NS\AceBundle\Tests\Twig;

use NS\AceBundle\Twig\WidgetExtension;
use PHPUnit\Framework\TestCase;

class WidgetExtensionTest extends TestCase
{
//    private $options = array(
//        'filter_id' => 'filters',
//        'open_icon' => 'fa-plus',
//        'close_icon' => 'fa-minus',
//        'target_text' => 'Filters',
//        'include_expander' => true,
//    );

    /**
     * @param $options
     * @param $contains
     *
     * @dataProvider getOptions
     */
    public function testGetFilterString(array $options, $contains): void
    {
        $extension = new WidgetExtension();
        $output    = $extension->getFilterString(array_merge($options, ['start_open' => false]));

        foreach ($contains as $contain) {
            $this->assertContains($contain, $output);
        }
    }

    public function getOptions(): array
    {
        $defaults = [
            'filter_id' => 'filters',
            'open_icon' => 'fa-plus',
            'close_icon' => 'fa-minus',
            'target_text' => 'Filters',
        ];

        $second              = $defaults;
        $second['open_icon'] = 'fa-cog';

        return [
            [$defaults, $defaults],
            [$second, $second],
        ];
    }

    public function testGetFullScreen(): void
    {
        $extension = new WidgetExtension();
        $this->assertNull($extension->getFullScreenString(['include_expander' => false]));
        $this->assertNotEmpty($extension->getFullScreenString(['include_expander' => true]));
    }
}
