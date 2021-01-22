<?php

namespace NS\AceBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WidgetExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $options = [
        'filter_id' => 'filters',
        'open_icon' => 'fa-plus',
        'close_icon' => 'fa-minus',
        'target_text' => 'Filters',
        'include_expander' => true,
        'start_open' => false,
    ];

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('widget_filter_toolbox', [$this, 'renderFilterToolbox'], ['is_safe'=> ['html']])
        ];
    }

    /**
     * @param array $options
     * @return string
     */
    public function renderFilterToolbox(array $options = [])
    {
        $applied = array_merge($this->options,$options);

        return sprintf('%s%s',$this->getFilterString($applied),$this->getFullScreenString($applied));
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getFilterString(array $options = [])
    {
        return sprintf('<div class="widget-toolbar">
                            <a href="#%s" data-toggle="collapse" class="white small"><i class="ace-icon fa %s" data-icon-hide="%s" data-icon-show="%s"></i> %s</a>
                        </div>',$options['filter_id'],($options['start_open']) ?  $options['close_icon']:$options['open_icon'],$options['close_icon'],$options['open_icon'],$options['target_text']);
    }

    /**
     * @param array $options
     * @return null|string
     */
    public function getFullScreenString(array $options = [])
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
