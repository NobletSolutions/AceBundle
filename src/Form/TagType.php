<?php

namespace NS\AceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use NS\AceBundle\Form\Transformer\TextToArrayTransformer;

/**
 * @author http://loopj.com/jquery-tokeninput/
 */
class TagType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'caseInsensitive' => true,
            'allowDuplicates' => false,
            'autocompleteOnComma' => false,
            'arrayOutput' => false,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['source'])) {
            sort($options['source']);
            $view->vars['attr']['data-source'] = json_encode($options['source']);
        }

        $view->vars['attr']['class'] = 'nsTag';
        $view->vars['attr']['data-case-insensitive'] = $options['caseInsensitive'];
        $view->vars['attr']['data-allow-duplicates'] = $options['allowDuplicates'];
        $view->vars['attr']['data-autocomplete-on-comma'] = $options['autocompleteOnComma'];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['arrayOutput']) {
            $builder->addViewTransformer(new TextToArrayTransformer());
        }
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
