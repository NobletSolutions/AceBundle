<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 23/08/16
 * Time: 2:35 PM
 */

namespace NS\AceBundle\Form\Extensions;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HiddenParentChildExtension extends AbstractTypeExtension
{
    /** @var array */
    private $config = [];

    /** @var array */
    private $prototypes = [];

    private function collectChildViews(FormView $view, FormInterface $form, array &$display)
    {
        foreach ($view->children as $subViewName => $subChildView) {
            if (!$form[$subViewName]->getConfig()->hasOption('hidden')) {
                $display[] = $subChildView->vars['full_name'];
            }

            if ($subChildView->children) {
                $this->collectChildViews($subChildView, $form[$subViewName], $display);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /** @var FormInterface $childForm */
        foreach ($form as $name => $childForm) {
            if ($childForm->getConfig()->hasOption('hidden')) {
                $config = $childForm->getConfig()->getOption('hidden');

                if (isset($config['parent'], $view[$config['parent']])) {
                    $parentView = $view[$config['parent']];
                    $fullName   = $view[$name]->vars['full_name'];

                    $display = [$fullName];
                    $this->collectChildViews($view[$name], $childForm, $display);

                    // TODO determine if this is the best way to test if we're dealing with a prototype form
                    if (strpos($fullName, '__') === false) {
                        if (!isset($this->config[$parentView->vars['full_name']])) {
                            $this->config[$parentView->vars['full_name']] = [['display' => $display, 'values' => (array)$config['value']]];
                        } else {
                            $this->config[$parentView->vars['full_name']][] = ['display' => $display, 'values' => (array)$config['value']];
                        }
                    } else {
                        if (!isset($this->prototypes[$parentView->vars['full_name']])) {
                            $this->prototypes[$parentView->vars['full_name']] = [['display' => $display, 'values' => (array)$config['value']]];
                        } else {
                            $this->prototypes[$parentView->vars['full_name']][] = ['display' => $display, 'values' => (array)$config['value']];
                        }
                    }
                }

                if (isset($config['children'])) {
                    $childView = $view[$name];
                    if (strpos($childView->vars['full_name'], '__') === false) {
                        foreach ($config['children'] as $id => $value) {
                            if (!isset($this->config[$childView->vars['full_name']])) {
                                $this->config[$childView->vars['full_name']] = [];
                            }

                            $newId                                         = str_replace('#', '#' . $childView->vars['id'] . '_', $id);
                            $this->config[$childView->vars['full_name']][] = ['display' => $newId, 'values' => $value];
                        }
                    } else {
                        foreach ($config['children'] as $id => $value) {
                            if (!isset($this->prototypes[$childView->vars['full_name']])) {
                                $this->prototypes[$childView->vars['full_name']] = [];
                            }

                            $newId                                             = str_replace('#', '#' . $childView->vars['id'] . '_', $id);
                            $this->prototypes[$childView->vars['full_name']][] = ['display' => $newId, 'values' => $value];
                        }
                    }
                }
            }
        }

        if ($form->getParent()) {
            return;
        }

        // Ignore the CSRF _token field which comes in without a parent
        if ($form->getName() === $options['csrf_field_name'] && $form->getConfig()->getMapped() === false) {
            return;
        }

        if (!empty($this->config)) {
            $view->vars['attr'] = isset($view->vars['attr']) ? array_merge($view->vars['attr'], ['data-context-config' => json_encode($this->config)]) : ['data-context-config' => json_encode($this->config)];
        }

        if (!empty($this->prototypes)) {
            $view->vars['attr'] = isset($view->vars['attr']) ? array_merge($view->vars['attr'], ['data-context-prototypes' => json_encode($this->prototypes)]) : ['data-context-prototypes' => json_encode($this->prototypes)];
            if (empty($this->config)) {
                $view->vars['attr'] = array_merge($view->vars['attr'], ['data-context-config' => '[]']);
            }
        }

        // we reset these here because if we're handling more than one form in a given request they become cumulative
        // We should investigate namespacing the form configs?
        $this->config     = [];
        $this->prototypes = [];
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['hidden']);
        $resolver->setAllowedTypes('hidden', 'array');
        $resolver->setAllowedValues('hidden', function ($config) {
            if (!isset($config['children']) && !isset($config['parent'])) {
                return false;
            }

            if (isset($config['parent']) && !isset($config['value'])) {
                return false;
            }

            return true;
        });
    }
}
