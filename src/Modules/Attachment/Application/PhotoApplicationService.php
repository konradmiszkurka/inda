<?php

declare(strict_types=1);

namespace App\Modules\Attachment\Application;

use App\Lib\Domain\Result\Result;
use App\Lib\Domain\Result\ResultInterface;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\Attachment\Domain\Photo\Exception\DirectoryException;
use App\Modules\Attachment\Domain\Photo\Exception\DirectoryPermissionException;
use App\Modules\Attachment\Domain\Photo\Exception\FileDecodeException;
use App\Modules\Attachment\Domain\Photo\Exception\FileException;
use App\Modules\Attachment\Domain\Photo\Interfaces\FormDataInterface;
use App\Modules\Attachment\Domain\Photo\Interfaces\PhotoBase64DataInterface;
use App\Modules\Attachment\Domain\Photo\PhotoService;
use App\Modules\Attachment\UI\Request\Base64PhotoData;
use App\Modules\Attachment\UI\Request\FormPhotoData;
use App\Modules\User\UI\Admin\Request\PhotoData;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PhotoApplicationService
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
     * @var PhotoService
     */
    private $service;

    public function __construct(
        ValidatorInterface $validator,
        ContainerInterface $container,
        PhotoService $service
    ) {
        $this->validator = $validator;
        $this->container = $container;
        $this->service = $service;
    }

    public function createPhoto(FormDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), self::class . '-create');
        $this->saveFileInDir($data, $data->getPathFile());

        if ($result->hasErrors()) {
            return $result;
        }

        $entity = $this->service->create($data);
        $result->setObject($entity);

        return $result;
    }

    public function createBase64Photo(PhotoBase64DataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), self::class . 'create-base64');
        $this->saveBase64InDir($data, $data->getPathFile());

        if ($result->hasErrors()) {
            return $result;
        }
        $saveData = (new FormPhotoData())
            ->setName($data->getName())
            ->setDescription($data->getDescription())
            ->setFile($data->getFile());
        $saveData->setPath($data->getPath());
        $saveData->setMimeType($data->getMimeType());

        $entity = $this->service->create($saveData);
        $result->setObject($entity);

        return $result;
    }

    public function createPhotoExternal(
        object $externalEntity,
        string $name,
        UploadedFile $file,
        ?string $description = null
    ): PhotoEntity {
        if ($description === null) {
            $description = get_class($externalEntity);
        }

        return $this->createPhoto(
            (new PhotoData())
                ->setName($name)
                ->setDescription($description)
                ->setFile($file)
        )->getObject();
    }

    public function createBase64PhotoExternal(
        object $externalEntity,
        string $name,
        string $base64,
        ?string $description = null
    ): PhotoEntity {
        if ($description === null) {
            $description = get_class($externalEntity);
        }

        return $this->createBase64Photo(
            (new Base64PhotoData())
                ->setName($name)
                ->setDescription($description)
                ->setBase64($base64)
        )->getObject();
    }

    public function updatePhoto(PhotoEntity $entity, FormDataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), 'photo-attachment');
        if ($data->getFile()) {
            $this->saveFileInDir($data, $data->getPathFile());
        }

        if ($result->hasErrors()) {
            return $result;
        }

        $this->service->update($entity, $data);

        return $result;
    }

    public function removePhoto(PhotoEntity $entity): ResultInterface
    {
        $result = new Result();

        $this->service->remove($entity);

        return $result;
    }

    private function saveFileInDir(FormDataInterface $data, ?string $pathImage = null): void
    {
        /** @var UploadedFile $file */
        $file = $data->getFile();
        $pathImageInternal = $this->container->getParameter('photo_directory_internal');

        if ($pathImage === null) {
            $pathImage = $this->container->getParameter('photo_directory');
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

        $data->setPath($pathImageInternal . $fileName);
        $data->setMimeType($mimeType);
    }

    private function saveBase64InDir(PhotoBase64DataInterface $data, ?string $pathImage = null): void
    {
        $pathImageInternal = $this->container->getParameter('photo_directory_internal');

        if ($pathImage === null) {
            $pathImage = $this->container->getParameter('photo_directory');
        }
        if ($data->getBase64() === false) {
            throw new FileDecodeException(sprintf('Can not decode file: %s', $data->getName()));
        }
        if (!is_dir($pathImage)) {
            throw new DirectoryException(sprintf('Uploaded directory (%s) doesn`t exist', $pathImage));
        }
        if (!is_writable($pathImage)) {
            throw new DirectoryPermissionException('No write permission to uploaded directory');
        }

        list(, $base64) = explode(';', $data->getBase64());
        list(, $base64) = explode(',', $base64);

        $extensionExplode = explode('.', $data->getName());
        $extension = end($extensionExplode);
        $name = str_replace(
            sprintf('.%s', $extension),
            '',
            $data->getName()
        );
        $mimeType = str_replace(
            sprintf('%s.', $name),
            '',
            $data->getName()
        );
        $fileName = sprintf(
            '%s.%s',
            uniqid($name, true),
            $extension
        );

        $file = $pathImage . DIRECTORY_SEPARATOR . $fileName;
        if (file_put_contents($file, base64_decode($base64)) === false) {
            throw new FileException('Can not save file to uploaded directory');
        }

        $data->setFile((new UploadedFile($file, $data->getName())));
        $data->setPath($pathImageInternal . $fileName);
        $data->setMimeType($mimeType);
    }
}