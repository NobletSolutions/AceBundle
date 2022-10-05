<?php

namespace NS\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use NS\AceBundle\Form\Transformer\EntityOrCreate;
use NS\AceBundle\Form\AutocompleterType;

class EntityOrCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $entityOptions = array_merge($options['entity_options'], ['class' => $options['class']]);

        $builder->add('finder', AutocompleterType::class, $entityOptions);

        if ($options['include_form']) {
            $builder
                ->add('createFormClicked',HiddenType::class,['data'=>'finder'])
                ->add('createForm', $options['type'], $options['create_options'] ?? []);
        }

        $builder->addViewTransformer(new EntityOrCreate((isset($entityOptions['multiple']) && $entityOptions['multiple']), $options['include_form'], $options['class']));
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['include_button'] = $options['include_button'];
        $view->vars['include_form']   = $options['include_form'];

        if (isset($options['modal_size'])) {
            $view->vars['modal_size'] = sprintf("modal-%d", $options['modal_size']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['class', 'type']);
        $resolver->setDefined(['create_options', 'modal_size']);
        $resolver->setDefaults([
            'include_button' => true,
            'include_form'   => true,
            'error_bubbling' => false,
            'entity_options' => [],
        ]);

        $resolver->setAllowedValues('modal_size', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]);
    }

    public function getBlockPrefix(): string
    {
        return 'entity_create';
    }
}
