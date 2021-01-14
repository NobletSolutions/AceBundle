<?php


namespace NS\AceBundle\Form;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

trait Select2Input
{
    /** @var RouterInterface */
    private $router;

    protected $params = ['url', 'method', 'allowClear', 'closeOnSelect', 'debug', 'maximumInputLength', 'maximumSelectionLength', 'minimumInputLength', 'minimumResultsForSearch',
                         'initCallback', 'ajaxDelay', 'tags'];

    protected function getOptions(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $options = $form->getConfig()->getOptions();

        $choices = [];

        if (is_array($data)) {
            foreach ($data as $choice) {
                $choices[$choice] = $choice;
            }
        } else {
            $choices[$data] = $data;
        }

        $options['choices'] = $choices;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array_merge($this->params, ['transformer', 'route', 'routeParams', 'config', 'class'/*, 'property', 'transformer'*/]));
        $resolver->setDefault('minimumInputLength', 2);
        $resolver->setAllowedTypes('allowClear', ['boolean']);
        $resolver->setAllowedTypes('closeOnSelect', ['boolean']);
        $resolver->setAllowedTypes('debug', ['boolean']);
        $resolver->setAllowedTypes('maximumInputLength', ['integer']);
        $resolver->setAllowedTypes('maximumSelectionLength', ['integer']);
        $resolver->setAllowedTypes('minimumInputLength', ['integer']);
        $resolver->setAllowedTypes('minimumResultsForSearch', ['integer']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['route'])) {
            $url            = $this->router->generate($options['route'], $options['routeParams'] ?? []);
            $options['url'] = $url;
        }

        foreach ($this->params as $param) {
            if (isset($options[$param]) && $options[$param]) {
                $pname                             = implode('-', preg_split('/(?=[A-Z])/', $param, -1, PREG_SPLIT_NO_EMPTY));
                $view->vars['attr']["data-$pname"] = $options[$param];
            }
        }

        if (isset($options['config']) && $options['config']) {
            foreach ($options['config'] as $key => $param) {
                $pname                             = implode('-', preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY));
                $view->vars['attr']["data-$pname"] = $param;
            }
        }

        if (isset($options['url'])) {
            $view->vars['choices'] = [];//Don't pre-populate the dropdown if we're loading results via ajax
        }

        $view->vars['attr']['class'] = isset($view->vars['attr']['class']) ? $view->vars['attr']['class'] . 'nsSelect2' : 'nsSelect2';
    }
}
