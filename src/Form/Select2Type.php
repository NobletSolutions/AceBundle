<?php


namespace NS\AceBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;

class Select2Type extends AbstractType
{
    use Select2Input;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event)
    {
        $data   = $event->getData();
        $form   = $event->getForm();
        $config = $form->getConfig();

        if ($config->getOption('url')) {
            $name   = $form->getName();
            $parent = $form->getParent();

            if (!in_array($data, $form->getConfig()->getOption('choices'))) {
                $parent->add($name, __CLASS__, $this->getOptions($event));

                $newForm = $parent->get($name);
                $newData = $newForm->getData();

                $newForm->submit($data);
            }
        }
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
