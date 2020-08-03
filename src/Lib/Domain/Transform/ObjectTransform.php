<?php
declare(strict_types=1);

namespace App\Lib\Domain\Transform;

class ObjectTransform extends AbstractTransform
{
    /**
     * @var object
     */
    protected $data;

    public function setData(object $data): ObjectTransform
    {
        $this->data = $data;
        return $this;
    }

    public function getType(): string
    {
        return 'object';
    }

    public function getResult(): object
    {
        foreach ($this->getVariables() as $variableObject) {
            $variable = $variableObject->name;
            $methodGetName = 'get' . ucfirst($variable);
            $methodIsName = 'is' . ucfirst($variable);

            if (method_exists($this->data, $methodIsName)) {
                $methodSetName = 'set' . ucfirst($variable);
                if (method_exists($this->exitClass, $methodSetName)) {
                    $this->exitClass->$methodSetName($this->data->$methodIsName());
                } else {
                    $this->exitClass->$variable = $this->data->$methodIsName();
                }
            } elseif (method_exists($this->data, $methodGetName)) {
                $methodSetName = 'set' . ucfirst($variable);
                if (method_exists($this->exitClass, $methodSetName)) {
                    $this->exitClass->$methodSetName($this->data->$methodGetName());
                } else {
                    $this->exitClass->$variable = $this->data->$methodGetName();
                }
            }
        }

        return $this->exitClass;
    }
}
