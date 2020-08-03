<?php
declare(strict_types=1);

namespace App\Lib\Domain\Params;

use App\Lib\Domain\Criteria\CriteriaInterface;
use App\Lib\Domain\Params\Exception\FieldIsNotAllowed;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

final class Filters implements CriteriaInterface
{
    /**
     * @var array
     * @Serializer\Type("array")
     * @Assert\All({
     *      @Assert\Collection(
     *          fields={
     *               "in" = @Assert\Optional({
     *                   @Assert\Type("array")
     *               }),
     *               "from" = @Assert\Optional({
     *                  @Assert\NotBlank(),
     *                  @Assert\Type("scalar")
     *               }),
     *               "to" = @Assert\Optional({
     *                  @Assert\NotBlank(),
     *                  @Assert\Type("scalar")
     *               }),
     *               "prefix" = @Assert\Optional({
     *                  @Assert\NotNull(),
     *                  @Assert\Type("scalar")
     *               }),
     *               "is" = @Assert\Optional({
     *                  @Assert\NotBlank(),
     *                  @Assert\Type("scalar")
     *               }),
     *               "notIs" = @Assert\Optional({
     *                  @Assert\NotBlank(),
     *                  @Assert\Type("scalar")
     *               }),
     *               "notIn" = @Assert\Optional({
     *                  @Assert\Type("array")
     *               }),
     *
     *          },
     *          allowMissingFields=true
     *      )
     * })
     */
    private $fieldsCriteria = [];
    /**
     * @var array|null 
     */
    private $allowedFields = null;

    public function getFieldCriteria(string $field): ?array
    {
        if (null !== $this->allowedFields) {
            if (in_array($field, $this->allowedFields, true)) {
                return $this->fieldsCriteria[$field] ?? null;
            }
            throw new FieldIsNotAllowed('Requested field is not allowed');
        }

        return $this->fieldsCriteria[$field] ?? null;
    }

    public function addFieldCriteria(string $field, array $criteria): void
    {
        $this->fieldsCriteria[$field] = $criteria;
    }

    public function applyAllowedFields(array $allowedFields): void
    {
        $this->allowedFields = $allowedFields;
    }

    public function addAllowedField(string $field): void
    {
        $this->allowedFields[] = $field;
    }

    public function getAllowedFields(): array
    {
        return $this->allowedFields;
    }

    public function getFieldsCriteria(): array
    {
        $avCriteria = [];
        if (false === empty($this->allowedFields)) {
            foreach ($this->allowedFields as $allowedField) {
                if (isset($this->fieldsCriteria[$allowedField])) {
                    $avCriteria[$allowedField] = $this->fieldsCriteria[$allowedField];
                }
            }
        } else {
            $avCriteria = $this->fieldsCriteria;
        }

        return $avCriteria;
    }
}
