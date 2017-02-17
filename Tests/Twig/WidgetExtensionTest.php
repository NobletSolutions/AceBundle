<?php

namespace NS\AceBundle\Tests\Twig;
use NS\AceBundle\Twig\WidgetExtension;

/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 29/04/16
 * Time: 1:01 PM
 */
class WidgetExtensionTest extends \PHPUnit_Framework_TestCase
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
    public function testGetFilterString($options,$contains)
    {
        $extension = new WidgetExtension();
        $output = $extension->getFilterString(array_merge($options,['start_open'=>false]));

        foreach($contains as $contain) {
            $this->assertContains($contain, $output);
        }
    }

    public function getOptions()
    {
        $defaults = array(
            'filter_id' => 'filters',
            'open_icon' => 'fa-plus',
            'close_icon' => 'fa-minus',
            'target_text' => 'Filters',
        );

        $second = $defaults;
        $second['open_icon'] = 'fa-cog';

        return array(
            array($defaults, $defaults),
            array($second, $second),
        );
    }

    public function testGetFullScreen()
    {
        $extension = new WidgetExtension();
        $this->assertNull($extension->getFullScreenString(array('include_expander'=>false)));
        $this->assertNotEmpty($extension->getFullScreenString(array('include_expander'=>true)));
    }
}
