<?php

namespace NS\AceBundle\Tests\Form;

use NS\AceBundle\Form\Select2Type;
use NS\AceBundle\Tests\BaseFormTestType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Routing\RouterInterface;

class Select2TypeTest extends BaseFormTestType
{
    private RouterInterface $router;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new Select2Type($this->router);

        return [new PreloadedExtension([$type], [])];
    }

    /**
     * @Ai-Generated
     */
    public function testFormTypeBasic(): void
    {
        $form = $this->factory
            ->createBuilder()
            ->add('select', Select2Type::class, [
                'choices' => ['Option 1' => 'option1', 'Option 2' => 'option2']
            ])
            ->getForm();

        $view = $form->createView();

        $this->assertArrayHasKey('select', $view);
        $this->assertArrayHasKey('attr', $view['select']->vars);
        $this->assertArrayHasKey('style', $view['select']->vars['attr']);
        $this->assertStringContainsString('width: 100%', $view['select']->vars['attr']['style']);
    }

    /**
     * @Ai-Generated
     */
    public function testBuildViewAddsDefaultWidth(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'choices' => ['Test' => 'test']
        ]);

        $view = $form->createView();

        $this->assertEquals('width: 100%;', $view->vars['attr']['style']);
    }

    /**
     * @Ai-Generated
     */
    public function testBuildViewPreservesExistingStyle(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'choices' => ['Test' => 'test'],
            'attr' => ['style' => 'color: red;']
        ]);

        $view = $form->createView();

        $this->assertStringContainsString('color: red;', $view->vars['attr']['style']);
        $this->assertStringContainsString('width: 100%;', $view->vars['attr']['style']);
    }

    /**
     * @Ai-Generated
     */
    public function testBuildViewDoesNotDuplicateWidth(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'choices' => ['Test' => 'test'],
            'attr' => ['style' => 'width: 50%;']
        ]);

        $view = $form->createView();

        $this->assertEquals('width: 50%;', $view->vars['attr']['style']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithRoute(): void
    {
        $routeName = 'test_route';
        $this->router->expects($this->once())
            ->method('generate')
            ->with($routeName, [])
            ->willReturn('/test/route');

        $form = $this->factory->create(Select2Type::class, null, [
            'route' => $routeName,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-url', $view->vars['attr']);
        $this->assertEquals('/test/route', $view->vars['attr']['data-url']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithRouteParams(): void
    {
        $routeName = 'test_route';
        $routeParams = ['id' => 123];

        $this->router->expects($this->once())
            ->method('generate')
            ->with($routeName, $routeParams)
            ->willReturn('/test/route/123');

        $form = $this->factory->create(Select2Type::class, null, [
            'route' => $routeName,
            'routeParams' => $routeParams,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertEquals('/test/route/123', $view->vars['attr']['data-url']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithUrl(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'url' => '/custom/url',
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-url', $view->vars['attr']);
        $this->assertEquals('/custom/url', $view->vars['attr']['data-url']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithAllowClear(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'allowClear' => true,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-allow-clear', $view->vars['attr']);
        $this->assertEquals(true, $view->vars['attr']['data-allow-clear']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithMinimumInputLength(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'minimumInputLength' => 3,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-minimum-input-length', $view->vars['attr']);
        $this->assertEquals(3, $view->vars['attr']['data-minimum-input-length']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithPlaceholder(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'placeholder' => 'Select an option',
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-placeholder', $view->vars['attr']);
        $this->assertEquals('Select an option', $view->vars['attr']['data-placeholder']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithLanguageConfig(): void
    {
        $languageConfig = ['noResults' => 'No results found'];

        $form = $this->factory->create(Select2Type::class, null, [
            'language' => $languageConfig,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-language-config', $view->vars['attr']);
        $this->assertEquals(json_encode($languageConfig), $view->vars['attr']['data-language-config']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithCustomConfig(): void
    {
        $customConfig = ['customOption' => 'customValue'];

        $form = $this->factory->create(Select2Type::class, null, [
            'config' => $customConfig,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-custom-option', $view->vars['attr']);
        $this->assertEquals('customValue', $view->vars['attr']['data-custom-option']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithTemplateResult(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'templateResult' => 'myCustomTemplate',
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('data-nstemplateresult', $view->vars['attr']);
        $this->assertEquals('myCustomTemplate', $view->vars['attr']['data-nstemplateresult']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormAddsNsSelect2Class(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertArrayHasKey('class', $view->vars['attr']);
        $this->assertEquals('nsSelect2', $view->vars['attr']['class']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormAppendsNsSelect2ClassToExisting(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'choices' => [],
            'attr' => ['class' => 'my-custom-class']
        ]);

        $view = $form->createView();

        $this->assertStringContainsString('my-custom-class', $view->vars['attr']['class']);
        $this->assertStringContainsString('nsSelect2', $view->vars['attr']['class']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithUrlClearsChoices(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'url' => '/test/url',
            'choices' => ['Option 1' => 'option1', 'Option 2' => 'option2']
        ]);

        $view = $form->createView();

        $this->assertEmpty($view->vars['choices']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithUrlPreservesExistingValue(): void
    {
        $form = $this->factory->create(Select2Type::class, 'existing_value', [
            'url' => '/test/url',
            'choices' => ['Existing' => 'existing_value']
        ]);

        $view = $form->createView();

        $this->assertNotEmpty($view->vars['choices']);
    }

    /**
     * @Ai-Generated
     */
    public function testPreSubmitWithDynamicChoice(): void
    {
        $this->router->method('generate')->willReturn('/test/url');

        $form = $this->factory
            ->createBuilder()
            ->add('select', Select2Type::class, [
                'url' => '/test/url',
                'choices' => ['Option 1' => 'option1']
            ])
            ->getForm();

        $form->submit(['select' => 'new_option']);

        $this->assertEquals('new_option', $form->get('select')->getData());
    }

    /**
     * @Ai-Generated
     */
    public function testPreSubmitWithExistingChoice(): void
    {
        $form = $this->factory
            ->createBuilder()
            ->add('select', Select2Type::class, [
                'url' => '/test/url',
                'choices' => ['Option 1' => 'option1']
            ])
            ->getForm();

        $form->submit(['select' => 'option1']);

        $this->assertEquals('option1', $form->get('select')->getData());
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithMultipleParameters(): void
    {
        $form = $this->factory->create(Select2Type::class, null, [
            'allowClear' => true,
            'closeOnSelect' => false,
            'minimumInputLength' => 3,
            'maximumSelectionLength' => 5,
            'placeholder' => 'Select items',
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertTrue($view->vars['attr']['data-allow-clear']);
        $this->assertArrayNotHasKey('data-close-on-select', $view->vars['attr']);
        $this->assertEquals(3, $view->vars['attr']['data-minimum-input-length']);
        $this->assertEquals(5, $view->vars['attr']['data-maximum-selection-length']);
        $this->assertEquals('Select items', $view->vars['attr']['data-placeholder']);
    }

    /**
     * @Ai-Generated
     */
    public function testFormWithArrayParameter(): void
    {
        $ajaxConfig = ['delay' => 250, 'cache' => true];

        $form = $this->factory->create(Select2Type::class, null, [
            'language' => $ajaxConfig,
            'choices' => []
        ]);

        $view = $form->createView();

        $this->assertEquals(json_encode($ajaxConfig), $view->vars['attr']['data-language-config']);
    }

    /**
     * @Ai-Generated
     */
    public function testParentIsChoiceType(): void
    {
        $type = new Select2Type($this->router);

        $this->assertEquals('Symfony\Component\Form\Extension\Core\Type\ChoiceT ype', $type->getParent());
    }
}
