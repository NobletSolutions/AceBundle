<?php

namespace NS\AceBundle\Form;

use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Routing\RouterInterface;
use \NS\AceBundle\Form\Transformer\EntityToJson;
use \NS\AceBundle\Form\Transformer\CollectionToJson;

/**
 * Description of SwitchType
 *
 * @author gnat
 * @author mark
 * @author http://loopj.com/jquery-tokeninput/
 */
class AutocompleterType extends AbstractType
{
    /**
     *  @var $router RouterInterface
     */
    private $router;

    /**
     *  @var $entityMgr ObjectManager
     */
    private $entityMgr;

    /**
     * @param RouterInterface $router
     * @param ObjectManager $entityMgr
     */
    public function __construct(ObjectManager $entityMgr, RouterInterface $router = null)
    {
        $this->router    = $router;
        $this->entityMgr = $entityMgr;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['class']))
        {
            $transformer = ($options['multiple'] == true) ? new CollectionToJson($this->entityMgr, $options['class']) : new EntityToJson($this->entityMgr, $options['class']);

            $builder->addModelTransformer($transformer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('route', 'autocompleteUrl', 'class'));

        $resolver->setDefaults(array(
            'method'        => 'POST',
            'queryParam'    => 'q',
            'minChars'      => 2,
            'prePopulate'   => null,
            'hintText'      => 'Enter a search term',
            'noResultsText' => 'No results',
            'searchingText' => 'Searching',
            'multiple'      => false,
            'attr'          => array('class' => 'nsAutocompleter'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $ar   = array(
            'method'        => null,
            'queryParam'    => null,
            'minChars'      => null,
            'prePopulate'   => null,
            'hintText'      => null,
            'noResultsText' => null,
            'searchingText' => null
        );

        $opts = array_intersect_key($options, $ar);

        if (!isset($options['autocompleteUrl']) && $this->router && isset($options['route']))
            $view->vars['attr']['data-autocompleteUrl'] = $this->router->generate($options['route']);
        else if (isset($options['autocompleteUrl']))
            $view->vars['attr']['data-autocompleteUrl'] = $options['autocompleteUrl'];

        $view->vars['attr']['data-options'] = json_encode($opts);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'autocompleter';
    }
}