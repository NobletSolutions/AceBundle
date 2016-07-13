<?php

namespace NS\AceBundle\Form;

use \Doctrine\Common\Persistence\ObjectManager;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\OptionsResolver\OptionsResolver;
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
     * @param ObjectManager $entityMgr
     * @param RouterInterface $router
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
        if (isset($options['class'])) {
            $property    = (isset($options['property'])) ? $options['property'] : null;
            $transformer = ($options['multiple'] == true) ? new CollectionToJson($this->entityMgr, $options['class'], $property) : new EntityToJson($this->entityMgr, $options['class'], $property);

            $builder->addModelTransformer($transformer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('route', 'autocompleteUrl', 'class', 'property', 'icon', 'secondary-field', 'resultsFormatter', 'tokenFormatter'));

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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $ar   = array('method', 'queryParam', 'minChars', 'prePopulate', 'hintText','noResultsText', 'searchingText');
        $opts = array_intersect_key($options, array_flip($ar));

        $opts['tokenLimit'] = $options['multiple'] ? null : 1;

        if (!isset($options['autocompleteUrl']) && $this->router && isset($options['route'])) {
            $view->vars['attr']['data-autocompleteUrl'] = $this->router->generate($options['route']);
        } elseif (isset($options['autocompleteUrl'])) {
            $view->vars['attr']['data-autocompleteUrl'] = $options['autocompleteUrl'];
        }

        if (isset($options['icon'])) {
            $view->vars['icon'] = $options['icon'];
        }

        if (isset($options['secondary-field'])) {
            $view->vars['attr']['data-autocomplete-secondary-field'] = json_encode($options['secondary-field']);
        }

        if (isset($options['resultsFormatter'])) {
            $view->vars['attr']['data-resultsFormatter'] = $options['resultsFormatter'];
        }

        if (isset($options['tokenFormatter'])) {
            $view->vars['attr']['data-tokenFormatter'] = $options['tokenFormatter'];
        }

        $view->vars['attr']['data-options'] = json_encode($opts);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
}
