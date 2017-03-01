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

    /**
     * @inheritDoc
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!$form->getParent()) {
            $this->processForm($form, $view);

            if (!empty($this->config)) {
                $view->vars['attr'] = (isset($view->vars['attr'])) ? array_merge($view->vars['attr'], ['data-context-config' => json_encode($this->config)]) : ['data-context-config' => json_encode($this->config)];
            }
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
            if (!is_array($config)) {
                return false;
            }

            if (!isset($config['children']) && !isset($config['parent'])) {
                return false;
            }

            if (isset($config['parent']) && !isset($config['value'])) {
                return false;
            }

            return true;
        });
    }

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

    private function processParentConfig(array $config, FormInterface $childItem, FormView $view)
    {
        if (!isset($view[$config['parent']])) {
            throw new \InvalidArgumentException('Unable to find parent form field: ' . $childItem->getName());
        }

        $parentName = $view[$config['parent']]->vars['full_name'];
        $fullName = $this->findFormFullName($view, $childItem->getName());

        if ($fullName === null) {
            throw new \RuntimeException("Unable to locate view for " . $childItem->getName());
        }

        if (!isset($this->config[$parentName])) {
            $this->config[$parentName] = [['display' => (array)$fullName, 'values' => (array)$config['value']]];
        } else {
            $this->config[$parentName][] = ['display' => (array)$fullName, 'values' => (array)$config['value']];
        }
    }

    private function processChildConfig(array $hiddenConfig, FormInterface $childItem, FormView $view)
    {
        $parentName = $this->findFormFullName($view, $childItem->getName());
        if ($parentName === null) {
            throw new \RuntimeException("Unable to locate view for " . $childItem->getName());
        }

        if (!isset($config[$parentName])) {
            $this->config[$parentName] = [];
        }

        foreach ($hiddenConfig['children'] as $id => $values) {
            $this->config[$parentName][] = ['display' => (array)$id, 'values' => (array)$values];
        }
    }

    private function findFormFullName(FormView $view, $name)
    {
        if (isset($view[$name])) {
            return $view[$name]->vars['full_name'];
        }

        if ($view->count() > 0) {
            foreach ($view as $childView) {
                $retValue = $this->findFormFullName($childView, $name);
                if ($retValue) {
                    return $retValue;
                }
            }
        }

        return null;
    }
}
