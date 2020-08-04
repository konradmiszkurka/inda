<?php

declare(strict_types=1);

namespace App\Modules\Product\Application;

use App\Lib\Application\EmailService;
use App\Lib\Domain\Error\Error;
use App\Lib\Domain\Result\Result;
use App\Lib\Domain\Result\ResultInterface;
use App\Lib\Application\Error\ErrorCodes;
use App\Modules\Product\Domain\Product\Entity\ProductEntity;
use App\Modules\Product\Domain\Product\ProductService;
use App\Modules\Product\Domain\Product\Interfaces\DataInterface;
use App\Modules\User\Domain\User\Entity\UserEntity;
use Omines\DataTablesBundle\DataTable;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductApplicationService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var ProductService
     */
    private $service;
    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(
        ValidatorInterface $validator,
        ProductService $service,
        EmailService $emailService
    ) {
        $this->validator = $validator;
        $this->service = $service;
        $this->emailService = $emailService;
    }

    public function createProduct(DataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), self::class . 'create');

        if ($result->hasErrors()) {
            return $result;
        }

        $entity = $this->service->create($data);

        /**
         * Comment send email
         */
//        if ($entity) {
//            try {
//                $this->emailService->sendEmail(
//                    \Swift_Mailer::class,
//                    'Product Create',
//                    'application@html.pl',
//                    'fake@example.com',
//                'productCreate',
//                    [
//                    'productName' => $entity->getName()
//                    ]);
//            } catch (\Exception $exception) {
//                return $exception->getMessage();
//            }
//        }
        $result->setObject($entity);

        return $result;
    }

    public function updateProduct(ProductEntity $entity, DataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), self::class . 'update');

        $entity = $this->service->update($entity, $data);

        $result->setObject($entity);

        return $result;
    }
}