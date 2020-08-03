<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\Photo\Interfaces;

use Symfony\Component\Validator\Constraints as Assert;

interface PhotoBase64DataInterface extends FormDataInterface
{
    /**
     * @Assert\NotNull()
     */
    public function getBase64(): string;
}