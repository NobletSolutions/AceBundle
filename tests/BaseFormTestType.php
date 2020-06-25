<?php

namespace NS\AceBundle\Tests;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class BaseFormTestType extends TypeTestCase
{
    /**
     * @param FormInterface $form
     * @param array $formData
     */
    public function commonTest(FormInterface $form, $formData): void
    {
        $this->assertTrue($form->isSynchronized());
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
