<?php

declare(strict_types=1);

namespace App\Modules\Attachment\Application;

use App\Lib\Domain\Result\Result;
use App\Lib\Domain\Result\ResultInterface;
use App\Modules\Attachment\Domain\File\Entity\FileEntity;
use App\Modules\Attachment\Domain\File\FileService;
use App\Modules\Attachment\Domain\File\Interfaces\FormDataInterface;
use App\Modules\Attachment\UI\Request\FormFileData;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileApplicationService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var FileService
     */
    private $service;

    public function __construct(
        ValidatorInterface $validator,
        ContainerInterface $container,
        FileService $service
    ) {
        $this->validator = $validator;
        $this->container = $container;
        $this->service = $service;
    }

    public function createFile(FormDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), 'file-attachment');
        $this->saveFileInDir($data);

        if ($result->hasErrors()) {
            return $result;
        }

        $entity = $this->service->create($data);
        $result->setObject($entity);

        return $result;
    }

    public function createFileExternal(
        object $externalEntity,
        string $name,
        UploadedFile $file,
        string $category,
        ?string $description = null
    ): FileEntity {
        if ($description === null) {
            $description = get_class($externalEntity);
        }

        return $this->createFile(
            (new FormFileData())
                ->setName($name)
                ->setDescription($description)
                ->setCategory($category)
                ->setFile($file)
        )->getObject();
    }

    public function updateFile(FileEntity $entity, FormDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), 'file-attachment');
        if ($data->getFile()) {
            $this->saveFileInDir($data);
        }

        if ($result->hasErrors()) {
            return $result;
        }

        $this->service->update($entity, $data);

        return $result;
    }

    public function removeFile(FileEntity $entity): ResultInterface
    {
        $result = new Result();

        $this->service->remove($entity);

        return $result;
    }

    private function saveFileInDir(FormDataInterface $data, ?string $pathImage = null): void
    {
        /** @var UploadedFile $file */
        $file = $data->getFile();

        if ($pathImage === null) {
            $pathImage = $this->container->getParameter('file_directory');
        }
        $name = str_replace(
            sprintf('.%s', $file->guessExtension()),
            '',
            $file->getClientOriginalName()
        );
        $mimeType = str_replace(
            sprintf('%s.', $name),
            '',
            $file->getClientOriginalName()
        );
        $fileName = sprintf(
            '%s.%s',
            uniqid($name, true),
            $file->guessExtension()
        );

        $file->move($pathImage, $fileName);

        $data->setPath($pathImage . $fileName);
        $data->setMimeType($mimeType);
    }
}