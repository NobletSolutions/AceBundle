<?php

namespace NS\AceBundle\Filter\Type;

use Doctrine\DBAL\Types\Type;
use Lexik\Bundle\FormFilterBundle\Filter\Condition\ConditionInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use NS\AceBundle\Service\DateFormatConverter;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType as ParentDateFilterType;

class DateFilterType extends AbstractType
{
    /**
     * @var DateFormatConverter
     */
    protected $converter;

    /**
     *
     * @param DateFormatConverter $converter
     */
    public function __construct(DateFormatConverter $converter = null)
    {
        $this->converter = ($converter) ?:new DateFormatConverter();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'required'               => false,
                'data_extraction_method' => 'default',
                'widget'   => 'single_text',
                'compound' => false,
                'format'   => $this->converter->getFormat(true),
                'html5'    => false,
                'apply_filter' => [$this,'filterDate'],
            ])
            ->setAllowedValues('data_extraction_method', ['default'])
        ;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= 'form-control date-picker';
        } else {
            $view->vars['attr']['class'] = 'form-control date-picker';
        }

        $view->vars['type'] = 'text';

        $view->vars['attr']['data-date-format'] = strtolower($options['format']);
        $view->vars['attr']['placeholder']      = $options['format'];
    }

    public function getParent(): string
    {
        return ParentDateFilterType::class;
    }

    /**
     * @param QueryInterface $filterQuery
     * @param $field
     * @param $values
     * @return ConditionInterface
     */
    public function filterDate(QueryInterface $filterQuery, $field, $values)
    {
        $value = $values['value'];
        if (!empty($value)) {
            $paramName = sprintf('p_%s', str_replace('.', '_', $field));
            $expr = $filterQuery->getExpressionBuilder();

            return $filterQuery->createCondition($expr->expr()->eq($field, ':' . $paramName), [$paramName => [$values['value'], Type::DATE]]);
        }
    }
}
