<?php

namespace NS\AceBundle\Tests\Twig;

use NS\AceBundle\Twig\BundleExistence;
use PHPUnit\Framework\TestCase;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BundleExistenceTest extends TestCase
{
    private BundleExistence $extension;

    protected function setUp(): void
    {
        $this->extension = new BundleExistence([
            'NSAceBundle'     => 'NS\AceBundle\NSAceBundle',
            'FrameworkBundle' => 'Symfony\Bundle\FrameworkBundle\FrameworkBundle',
            'TwigBundle'      => 'Symfony\Bundle\TwigBundle\TwigBundle',
        ]);
    }

    public function testMethodsAndInstanceOf(): void
    {
        self::assertInstanceOf(AbstractExtension::class, $this->extension);

        $functions = $this->extension->getFunctions();
        self::assertCount(1, $functions);
        self::assertInstanceOf(TwigFunction::class, $functions[0]);
        self::assertSame('bundleExists', $functions[0]->getName());
    }

    /** @dataProvider getValues */
    public function testExistence(string $bundle, bool $expected): void
    {
        self::assertSame($expected, $this->extension->bundleExists($bundle));
    }

    public function getValues(): array
    {
        return [
            ['NSAceBundle', true],
            ['FrameworkBundle', true],
            ['TwigBundle', true],
            ['NothingBundle', false],
        ];
    }
}
