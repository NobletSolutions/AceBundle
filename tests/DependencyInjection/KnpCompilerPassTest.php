<?php

namespace NS\AceBundle\Tests\DependencyInjection;

use NS\AceBundle\DependencyInjection\Compiler\KnpCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KnpCompilerPassTest extends TestCase
{
    public function test_dont_use_knp_menu(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $containerBuilder->expects($this->once())->method('getParameter')->with('ns_ace.use_knp_menu')->willReturn(false);
        $hasMap = [
            'knp_menu.renderer.twig.template'   => false,
            'knp_paginator.template.pagination' => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id . ' ' . print_r($hasMap, true));
        });

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_has_no_knp_menu(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects($this->once())->method('getParameter')->with('ns_ace.use_knp_menu')->willReturn(true);
        $hasMap = [
            'knp_menu.renderer.twig.template'   => false,
            'knp_paginator.template.pagination' => false,
            'knp_menu.renderer.twig.options'    => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });

        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', '@NSAce/Menu/menu.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_has_non_default_parameter(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map              = [
            ['ns_ace.use_knp_menu', true],
            ['knp_menu.renderer.twig.template', 'Non-default:Twig:knp_menu.html.twig'],
        ];
        $containerBuilder->method('getParameter')->willReturnMap($map);

        $hasMap = [
            'knp_menu.renderer.twig.template'   => true,
            'knp_paginator.template.pagination' => false,
            'knp_menu.renderer.twig.options'    => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });

        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_menu.renderer.twig.template', '@NSAce/Menu/menu.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_set_twig_template(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $hasMap           = [
            'knp_menu.renderer.twig.template'   => true,
            'knp_paginator.template.pagination' => false,
            'knp_menu.renderer.twig.options'    => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });

        $containerBuilder->method('getParameter')->willReturnCallback(static function (string $id) {
            switch ($id) {
                case 'ns_ace.use_knp_menu':
                    return true;
                case 'knp_menu.renderer.twig.template':
                    return 'KnpMenuBundle::menu.html.twig';
            }
        });

        $containerBuilder->expects($this->once())->method('setParameter')->with('knp_menu.renderer.twig.template', '@NSAce/Menu/menu.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_has_no_knp_pager(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $hasMap = [
            'knp_menu.renderer.twig.template'   => false,
            'knp_paginator.template.pagination' => false,
            'knp_menu.renderer.twig.options'    => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });

        $containerBuilder->expects($this->once())->method('getParameter')->with('ns_ace.use_knp_menu')->willReturn(false);

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_pager_has_non_default_parameter(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map              = [
            'ns_ace.use_knp_menu'               => false,
            'knp_paginator.template.pagination' => 'Something:Non:default.html.twig',
        ];
        $containerBuilder->method('getParameter')->willReturnCallback(static function (string $id) use ($map) {
            if (isset($map[$id])) {
                return $map[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });

        $hasMap = [
            'knp_paginator.template.pagination' => true,
            'knp_menu.renderer.twig.options' => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });

        $containerBuilder->expects($this->never())->method('setParameter')->with('knp_paginator.template.pagination', '@NSAce/Form/pagination.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }

    public function test_knp_pager_set_twig_template(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);
        $map              = [
            'ns_ace.use_knp_menu' => false,
            'knp_paginator.template.pagination' => 'KnpPaginatorBundle:Pagination:sliding.html.twig',
        ];
        $containerBuilder->method('getParameter')->willReturnCallback(static function (string $id) use ($map) {
            if (isset($map[$id])) {
                return $map[$id];
            }
            self::fail('Unexpected key: ' . $id);
        });

        $hasMap = [
            'knp_paginator.template.pagination' => true,
            'knp_menu.renderer.twig.options' => false,
        ];

        $containerBuilder->method('hasParameter')->willReturnCallback(static function (string $id) use ($hasMap) {
            if (isset($hasMap[$id])) {
                return $hasMap[$id];
            }

            self::fail('Unexpected key: ' . $id);
        });
        $containerBuilder->expects($this->once())->method('setParameter')->with('knp_paginator.template.pagination', '@NSAce/Form/pagination.html.twig');

        $compilerPass = new KnpCompilerPass();
        $compilerPass->process($containerBuilder);
    }
}
