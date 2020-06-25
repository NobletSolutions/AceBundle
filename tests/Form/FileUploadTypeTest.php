<?php

namespace NS\AceBundle\Tests\Form;

use \NS\AceBundle\Tests\BaseFormTestType;
use \NS\AceBundle\Form\FileUploadType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\NativeRequestHandler;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadTypeTest extends BaseFormTestType
{
    /**
     * @dataProvider requestHandlerProvider
     * @param $requestHandler
     */
    public function testFormType($requestHandler): void
    {
        $form = $this->factory->createBuilder(FileUploadType::class)->setRequestHandler($requestHandler)->getForm();
        $data = $this->createUploadedFileMock($requestHandler, __DIR__.'/Fixtures/Entity.php', 'Entity.php');

        $form->submit($data);

        $this->assertSame($data, $form->getData());
    }

    public function requestHandlerProvider(): array
    {
        return [
            [new HttpFoundationRequestHandler()],
            [new NativeRequestHandler()],
        ];
    }

    private function createUploadedFileMock(RequestHandlerInterface $requestHandler, $path, $originalName)
    {
        if ($requestHandler instanceof HttpFoundationRequestHandler) {
            return new UploadedFile($path, $originalName, null, 10, true);
        }

        return [
            'name' => $originalName,
            'error' => 0,
            'type' => 'text/plain',
            'tmp_name' => $path,
            'size' => 10,
        ];
    }
}
