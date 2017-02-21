<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/02/17
 * Time: 10:57 AM
 */

namespace NS\AceBundle\Tests;


class BaseTestCase extends \PHPUnit_Framework_TestCase
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
}
