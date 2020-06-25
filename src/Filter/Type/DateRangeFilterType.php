<?php

namespace NS\AceBundle\Filter\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Condition\ConditionInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of DateRangeFilterType
 *
 * @author gnat
 */
class DateRangeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('left_date', DateFilterType::class, $options['left_date_options']);
        $builder->add('right_date', DateFilterType::class, $options['right_date_options']);

        $builder->setAttribute('filter_value_keys', [
            'left_date'  => $options['left_date_options'],
            'right_date' => $options['right_date_options'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'required'               => false,
                'left_date_options'      => [],
                'right_date_options'     => [],
                'data_extraction_method' => 'value_keys',
                'apply_filter'           => [$this, 'filterDateRange'],
            ])
            ->setAllowedValues('data_extraction_method', ['value_keys']);
    }

    /**
     * @param QueryInterface $filterQuery
     * @param $field
     * @param $values
     * @return ConditionInterface
     */
    public function filterDateRange(QueryInterface $filterQuery, $field, $values)
    {
        $value  = $values['value'];

        if (isset($value['left_date'][0]) || isset($value['right_date'][0])) {
            $expr = $filterQuery->getExpressionBuilder();
            return $filterQuery->createCondition($expr->dateInRange($field, $value['left_date'][0], $value['right_date'][0]));
        }
    }
}
