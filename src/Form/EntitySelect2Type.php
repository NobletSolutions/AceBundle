<?php


namespace NS\AceBundle\Form;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class EntitySelect2Type extends AbstractType
{
    use Select2Input {
        configureOptions as _configureOptions;
    }

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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'preSetData']);
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $data = $event->getData();

        if ($data) {
            $data = is_iterable($data) ? $data : [$data];

            /** @var QueryBuilder $qb */
            $qb = $event->getForm()->getConfig()->getOption('query_builder');

            if ($qb) {
                $alias = $qb->getRootAlias();
                $qb->andWhere('e.id IN :es2_ids')
                   ->setParameter('es2_ids', $data);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $this->_configureOptions($resolver);

        $resolver->setDefault('query_builder', function (EntityRepository $er) {
            return $er->createQueryBuilder('e');
        });
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
