<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 22/04/16
 * Time: 11:01 AM
 */

namespace NS\AceBundle\Twig;


class WidgetExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $options = array(
        'filter_id' => 'filters',
        'open_icon' => 'fa-plus',
        'close_icon' => 'fa-minus',
        'target_text' => 'Filters',
        'include_expander' => true,
    );

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('widget_filter_toolbox',array($this,'renderFilterToolbox'),array('is_safe'=>array('html')))
        );
    }

    /**
     * @param array $options
     * @return string
     */
    public function renderFilterToolbox(array $options = array())
    {
        $applied = array_merge($this->options,$options);

        return sprintf('%s%s',$this->getFilterString($applied),$this->getFullScreenString($applied));
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getFilterString(array $options = array())
    {
        return sprintf('<div class="widget-toolbar">
                            <a href="#%s" data-toggle="collapse" class="white small"><i class="ace-icon fa %s" data-icon-hide="%s" data-icon-show="%s"></i> %s</a>
                        </div>',$options['filter_id'],$options['open_icon'],$options['close_icon'],$options['open_icon'],$options['target_text']);
    }

    /**
     * @param array $options
     * @return null|string
     */
    public function getFullScreenString(array $options = array())
    {
        $output = null;
        if($options['include_expander'] === true) {
            $output = '<div class="widget-toolbar no-border">
                    <a href="#" data-action="fullscreen" class="white">
                        <i class="ace-icon fa fa-expand"></i>
                    </a>
                </div>';
        }

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'widget_extension';
    }
}
