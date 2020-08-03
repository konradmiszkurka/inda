<?php 
declare(strict_types=1);

namespace App\Lib\Domain\Params;

use JMS\Serializer\ArrayTransformerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParamsConverter implements ParamConverterInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var string|null
     */
    private $validationErrorsArgument;
    /**
     * @var ArrayTransformerInterface
     */
    private $arrayTransformer;

    public function __construct(
        ArrayTransformerInterface $arrayTransformer,
        ValidatorInterface $validator,
        ?string $validationErrorsArgument = null
    ) {
        $this->validator = $validator;
        $this->validationErrorsArgument = $validationErrorsArgument ?? 'violationList';
        $this->arrayTransformer = $arrayTransformer;
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $query = $request->query->all();
        foreach ($query as $fieldName => $value) {
            if (is_string($value)) {
                $json = json_decode($value, true);
                if (null !== $json) { // check if is valid json
                    $query[$fieldName] = $json;
                }
            }
        }
        $query['sorting'] = $query['sort'] ?? null;

        /** @var Params $object */
        $object = $this->arrayTransformer->fromArray(
            $query,
            $configuration->getClass()
        );

        foreach ($query['filters'] ?? [] as $fieldName => $criteria) {
            $object->getFilters()->addFieldCriteria($fieldName, $criteria);
        }

        $request->attributes->set($configuration->getName(), $object);
        if (null !== $this->validator) {
            $errors = $this->validator->validate($object);

            $request->attributes->set(
                $this->validationErrorsArgument,
                $errors
            );
        }

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return Params::class === $configuration->getClass() && 'params_converter' === $configuration->getConverter();
    }
}
