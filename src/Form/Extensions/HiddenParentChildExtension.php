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

    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        // we reset these here because if we're handling more than one form in a given request they become cumulative
        // We should investigate namespacing the form configs?
        $this->config = [];
        $this->prototypes = [];

        if (!$form->getParent()) {
            // Ignore the CSRF _token field which comes in without a parent
            if ($form->getConfig()->getMapped() === false && $form->getName() == $options['csrf_field_name']) {
                return;
            }

            $this->processForm($form, $view);
            $this->processPrototypes($view);

            if (!empty($this->config)) {
                $view->vars['attr'] = (isset($view->vars['attr'])) ? array_merge($view->vars['attr'], ['data-context-config' => json_encode($this->config)]) : ['data-context-config' => json_encode($this->config)];
            }

            if (!empty($this->prototypes)) {
                foreach ($this->prototypes as $key => $value) {
                    if (strpos($key, '__name__') === false) {
                        unset($this->prototypes[$key]);
                    }
                }
                $view->vars['attr'] = (isset($view->vars['attr'])) ? array_merge($view->vars['attr'], ['data-context-prototypes' => json_encode($this->prototypes)]) : ['data-context-prototypes' => json_encode($this->prototypes)];
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $hiddenConfig = $form->getConfig()->getOption('hidden', false);
        if ($hiddenConfig !== false) {
            $view->vars['data-hidden'] = $hiddenConfig;
        }
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

    /**
     * @param FormInterface $form
     * @param FormView $view
     */
    public function processForm(FormInterface $form, FormView $view)
    {
        /** @var FormInterface $childItem */
        foreach ($form as $childItem) {
            $hiddenConfig = $childItem->getConfig()->getOption('hidden', false);
            if ($hiddenConfig) {
                if (isset($hiddenConfig['parent'])) {
                    $this->processParentConfig($hiddenConfig, $childItem, $view);
                }

                if (isset($hiddenConfig['children'])) {
                    $this->processChildConfig($hiddenConfig, $childItem, $view);
                }
            }

            if ($childItem->count() > 0) {
                $this->processForm($childItem, $view);
            }
        }
    }

    /**
     * @param FormView $view
     */
    private function processPrototypes(FormView $view)
    {
        foreach ($view as $childView) {
            if (isset($childView->vars['data-hidden'])) {
                $config = $childView->vars['data-hidden'];
                if (isset($config['parent'])) {
                    $parentView = $this->findView($config['parent'], $view);
                    $parentName = $parentView->vars['full_name'];
                    $childName = $this->findPrototypeFullName($childView);
                    if (!isset($this->prototypes[$parentName])) {
                        $this->prototypes[$parentName] = [['display' => $childName, 'values' => (array)$config['value']]];
                    } else {
                        $this->prototypes[$parentName][] = ['display' => $childName, 'values' => (array)$config['value']];
                    }
                }
            }

            if (isset($childView->vars['prototype'])) {
                $this->processPrototype($childView->vars['prototype']);
            }

            if ($childView->count() > 0) {
                $this->processPrototypes($childView);
            }
        }
    }

    private function processPrototype(FormView $view)
    {
        foreach ($view->children as $prototypeChildView) {
            if (isset($prototypeChildView->vars['data-hidden'])) {
                $config = $prototypeChildView->vars['data-hidden'];
                if (isset($config['parent'])) {
                    $parentView = $this->findView($config['parent'], $view);
                    $parentName = $parentView->vars['full_name'];
                    $childName = $this->findPrototypeFullName($prototypeChildView);
                    if (!isset($this->prototypes[$parentName])) {
                        $this->prototypes[$parentName] = [['display' => $childName, 'values' => (array)$config['value']]];
                    } else {
                        $this->prototypes[$parentName][] = ['display' => $childName, 'values' => (array)$config['value']];
                    }
                }

                if (isset($config['children'])) {
                    foreach ($config['children'] as $id => $value) {
                        if (!isset($this->prototypes[$prototypeChildView->vars['full_name']])) {
                            $this->prototypes[$prototypeChildView->vars['full_name']] = [];// = ['display'=>'#something','value'=>'value'];
                        }

                        $this->prototypes[$prototypeChildView->vars['full_name']][] = ['display' => $id, 'value' => $value];
                    }
                }
            }

            if ($prototypeChildView->count() > 0) {
                $this->processPrototype($prototypeChildView);
            }
        }
    }

    /**
     * @param FormView $view
     * @return array
     */
    private function findPrototypeFullName(FormView $view)
    {
        if ($view->count() == 0) {
            return [$view->vars['full_name']];
        }

        $children = [];
        foreach ($view as $childView) {
            $children[] = $childView->vars['full_name'];
        }

        return $children;
    }

    /**
     * @param array $config
     * @param FormInterface $childItem
     * @param FormView $view
     */
    private function processParentConfig(array $config, FormInterface $childItem, FormView $view)
    {
        $parentView = $this->findView($config['parent'], $view);
        if (!$parentView) {
            throw new \InvalidArgumentException(sprintf('Unable to find parent form \'%s\' field \'%s\' \'%s\'', $config['parent'], $childItem->getName(), print_r(array_keys($view->children), true)));
        }

        $parentName = $parentView->vars['full_name'];
        $fullName = $this->findFormFullName($view, $childItem);

        if ($fullName === null) {
            throw new \RuntimeException("Unable to locate view for " . $childItem->getName());
        }

        if (!isset($this->config[$parentName])) {
            $this->config[$parentName] = [['display' => (array)$fullName, 'values' => (array)$config['value']]];
        } else {
            $this->config[$parentName][] = ['display' => (array)$fullName, 'values' => (array)$config['value']];
        }
    }

    /**
     * @param array $hiddenConfig
     * @param FormInterface $childItem
     * @param FormView $view
     */
    private function processChildConfig(array $hiddenConfig, FormInterface $childItem, FormView $view)
    {
        $parentName = $this->findFormFullName($view, $childItem);
        if ($parentName === null) {
            throw new \RuntimeException("Unable to locate view for " . $childItem->getName());
        }

        $parentName = is_array($parentName) ? current($parentName) : $parentName;

        if (!isset($this->config[$parentName])) {
            $this->config[$parentName] = [];
        }

        foreach ($hiddenConfig['children'] as $id => $values) {
            $this->config[$parentName][] = ['display' => (array)$id, 'values' => (array)$values];
        }
    }

    /**
     * @param $name
     * @param FormView $view
     * @return mixed|null|FormView
     */
    private function findView($name, FormView $view)
    {
        if (isset($view[$name])) {
            return $view[$name];
        }

        if ($view->count() > 0) {
            foreach ($view as $childView) {
                $retValue = $this->findView($name, $childView);
                if ($retValue) {
                    return $retValue;
                }
            }
        }

        return null;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @return array|null
     */
    private function findFormFullName(FormView $view, FormInterface $form)
    {
        $isCompound = ($form->getConfig()->getOption('compound', false) && $form->count() > 0);

        // Is a root form relative to the view
        if (isset($view[$form->getName()])) {
            if ($isCompound) {
                $names = [$view[$form->getName()]->vars['full_name']];
                /** @var FormView $childView */
                $childView = $view[$form->getName()];

                /** @var FormInterface $childItem */
                foreach ($form as $childItem) {
                    $names = array_merge($names, (array)$this->findFormFullName($childView, $childItem));
                }

                return $names;
            }

            return $view[$form->getName()]->vars['full_name'];
        }

        // Form is a sub-form of the root
        if ($view->count() > 0) {
            foreach ($view as $childView) {
                $retValue = $this->findFormFullName($childView, $form);
                if ($retValue) {
                    return $retValue;
                }
            }
        }

        return null;
    }
}
