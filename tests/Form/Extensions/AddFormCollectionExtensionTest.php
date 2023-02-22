<?php

namespace NS\AceBundle\Tests\Form\Extensions;

use NS\AceBundle\Form\Extensions\AddFormCollectionExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class AddFormCollectionExtensionTest extends TypeTestCase
{
    public function testNoOptionsSetNoVarsSet(): void
    {
        $form = $this->factory->create(CollectionType::class);
        $view = $form->createView();
        self::assertArrayHasKey('attr', $view->vars);
        self::assertArrayNotHasKey('data-collection', $view->vars['attr']);
        self::assertArrayNotHasKey('data-insert-position', $view->vars['attr']);
        self::assertArrayNotHasKey('data-scroll-to-view', $view->vars['attr']);
    }

    public function testSetAllOptions(): void
    {
        $form = $this->factory->create(CollectionType::class, null, [
            'add_form_collection' => 'some-collection',
            'add_form_insert_position' => 'prepend',
            'add_form_scroll_to_view' => true,
        ]);

        $view = $form->createView();
        self::assertArrayHasKey('attr', $view->vars);
        self::assertArrayHasKey('data-collection', $view->vars['attr']);
        self::assertSame('some-collection', $view->vars['attr']['data-collection']);
        self::assertArrayHasKey('target_collection', $view->vars);
        self::assertSame('some-collection', $view->vars['target_collection']);
        self::assertArrayHasKey('data-insert-position', $view->vars['attr']);
        self::assertSame('prepend', $view->vars['attr']['data-insert-position']);
        self::assertArrayHasKey('data-scroll-to-view', $view->vars['attr']);
        self::assertTrue($view->vars['attr']['data-scroll-to-view']);
    }

    /** @dataProvider getInvalidFormOptions */
    public function testInvalidFormOptions(array $params): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->factory->create(CollectionType::class, null, $params);
    }

    public function getInvalidFormOptions(): array
    {
        return [
            [['add_form_collection' => true]],
            [['add_form_collection' => false]],
            [['add_form_collection' => new \stdClass()]],
            [['add_form_scroll_to_view' => 'true']],
            [['add_form_scroll_to_view' => new \stdClass()]],
            [['add_form_insert_position' => true]],
            [['add_form_insert_position' => new \stdClass()]],
            [['add_form_insert_position' => 'a__end']],
            [['add_form_insert_position' => 'prep']],
        ];
    }

    protected function getExtensions(): array
    {
        $ext = new AddFormCollectionExtension();
        return [new PreloadedExtension([], [$ext->getExtendedType() => [$ext]])];
    }
}
