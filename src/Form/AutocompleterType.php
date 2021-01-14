<?php

namespace NS\AceBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use NS\AceBundle\Form\Transformer\EntityToJson;
use NS\AceBundle\Form\Transformer\CollectionToJson;

/**
 * @author http://loopj.com/jquery-tokeninput/
 * @deprecated This class has been deprecated in favor of NS\AceBundle\Form\Select2Type with multiple=true
 */
class AutocompleterType extends AbstractType
{
    /** @var RouterInterface */
    private $router;

    /** @var EntityManagerInterface */
    private $entityMgr;

    public function __construct(EntityManagerInterface $entityMgr, RouterInterface $router = null)
    {
        $this->entityMgr = $entityMgr;
        $this->router    = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['class'])) {
            $property = (isset($options['property'])) ? $options['property'] : null;

            if (!isset($options['transformer'])) {
                if ($options['multiple'] === true) {
                    $builder->addViewTransformer(new CollectionToJson($this->entityMgr, $options['class'], $property));
                } else {
                    $builder->addViewTransformer(new EntityToJson($this->entityMgr, $options['class'], $property));
                }
            } elseif ($options['transformer'] !== false) {
                $builder->addViewTransformer($options['transformer']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['route', 'autocompleteUrl', 'class', 'property', 'icon', 'secondary-field', 'resultsFormatter', 'tokenFormatter', 'transformer', 'tokenValue', 'allowFreeTagging', 'caching']);

        $resolver->setDefaults([
            'method'        => 'POST',
            'queryParam'    => 'q',
            'minChars'      => 2,
            'prePopulate'   => null,
            'hintText'      => 'Enter a search term',
            'noResultsText' => 'No results',
            'searchingText' => 'Searching',
            'multiple'      => false,
            'attr'          => ['class' => 'nsAutocompleter'],
            'caching'       => true,
            'include_input_group' => false,
        ]);

        $resolver->setNormalizer('multiple', function (Options $options, $multiple) {
            if (!empty($options['data_class']) && $multiple === true) {
                throw new InvalidOptionsException('You cannot set a data_class when you accept multiple results');
            }

            return $multiple;
        });
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $ar   = ['method', 'queryParam', 'minChars', 'prePopulate', 'hintText','noResultsText', 'searchingText', 'allowFreeTagging', 'caching'];
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

        if (isset($options['tokenValue'])) {
            $view->vars['attr']['data-tokenValue'] = $options['tokenValue'];
        }

        $view->vars['attr']['data-options'] = json_encode($opts);
        $view->vars['include_input_group'] = $options['include_input_group'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
}
