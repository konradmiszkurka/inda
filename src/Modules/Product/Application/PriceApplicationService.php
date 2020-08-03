<?php

declare(strict_types=1);

namespace App\Modules\Product\Application;

use App\Lib\Domain\Result\Result;
use App\Lib\Domain\Result\ResultInterface;
use App\Modules\Product\Domain\CurrencyLocale\Interfaces\CurrencyLocaleRepositoryInterface;
use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use App\Modules\Product\Domain\Price\PriceService;
use App\Modules\Product\Domain\Price\Interfaces\DataInterface;
use App\Modules\Product\UI\Admin\Request\FormPriceData;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PriceApplicationService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var PriceService
     */
    private $service;
    /**
     * @var CurrencyLocaleRepositoryInterface
     */
    private $currencyLocaleRepository;

    public function __construct(
        ValidatorInterface $validator,
        PriceService $service,
        CurrencyLocaleRepositoryInterface $currencyLocaleRepository
    ) {
        $this->validator = $validator;
        $this->service = $service;
        $this->currencyLocaleRepository = $currencyLocaleRepository;
    }

    public function create(DataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), self::class . 'create');

        if ($result->hasErrors()) {
            return $result;
        }

        $entity = $this->service->create($data);
        $result->setObject($entity);

        return $result;
    }

    public function update(PriceEntity $entity, DataInterface $data): ResultInterface
    {
        $result = new Result();

        $result->addFromViolations($this->validator->validate($data), self::class . 'update');

        $entity = $this->service->update($entity, $data);

        $result->setObject($entity);

        return $result;
    }

    public function createPriceWithCurrency(string $price, string $locale): ?PriceEntity
    {
        $currency = $this->currencyLocaleRepository->findByLocale($locale);

        if ($currency) {
           $price =  $this->create((new FormPriceData)
                ->setCurrency($currency->getValue())
                ->setValue($price));

           return $price->getObject();
        }

        return null;
    }
}