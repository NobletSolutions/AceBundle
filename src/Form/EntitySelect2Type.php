<?php


namespace NS\AceBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;

class EntitySelect2Type extends AbstractType
{
    use Select2Input;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['class'])) {
            if (!isset($options['transformer'])) {
            } elseif ($options['transformer'] !== false) {
                $builder->addViewTransformer($options['transformer']);
            }
        }

        $this->options = $options;
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $form->getParent()->add($form->getName(), EntitySelect2Type::class, $this->getOptions($event));

    }

    public function getParent()
    {
        return EntityType::class;
    }
}
