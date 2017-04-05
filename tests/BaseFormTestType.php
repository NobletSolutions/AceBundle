<?php

namespace NS\AceBundle\Tests;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Description of BaseFormTest
 *
 * @author gnat
 */
class BaseFormTestType extends TypeTestCase
{
    protected function createMock($originalClassName)
    {
        if (method_exists(parent::class, 'createMock')) {
            return parent::createMock($originalClassName);
        }

        $obj = $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning();

        if (method_exists($obj, 'disallowMockingUnknownTypes')) {
            $obj->disallowMockingUnknownTypes();
        }

        return $obj->getMock();
    }

    /**
     *
     * @param FormInterface $form
     * @param array $formData
     */
    public function commonTest(FormInterface $form, $formData)
    {
        $this->assertTrue($form->isSynchronized());
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
