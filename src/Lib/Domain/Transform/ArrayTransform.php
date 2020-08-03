<?php
declare(strict_types=1);

namespace App\Lib\Domain\Transform;

class ArrayTransform extends AbstractTransform
{
    /**
     * @var array 
     */
    protected $data;

    public function setData(array $data): ArrayTransform
    {
        $this->data = $data;
        return $this;
    }

    public function getType(): string
    {
        return 'array';
    }

    public function getResult(): object
    {
        foreach ($this->getVariables() as $variableObject) {
            $variable = $variableObject->name;
            if (array_key_exists($variable, $this->data)) {
                $methodSetName = 'set' . ucfirst($variable);

                if (method_exists($this->exitClass, $methodSetName)) {
                    $this->exitClass->$methodSetName($this->data[$variable]);
                }
            }
        }

        return $this->exitClass;
    }
}
