<?php
declare(strict_types=1);

namespace App\Lib\Domain\DataTable;

trait ButtonTrait
{
    public function createButtons(array $buttons, string $moreBtns = null): string
    {
        if ($moreBtns === null) {
            $moreBtns = '';
        }

        $btns = '';

        foreach ($buttons as $button) {
            $btns .= '<a href="' . $button['uri'] . '" class="btn btn-sm ' . $button['class'] . '"' . (isset($button['confirm']) ? ' data-toggle="confirm"' : '') . '><i class="' . $button['icon'] . '"></i>' . (isset($button['name']) ? '&nbsp;' . $button['name'] : '') . '</a>';
        }

        return '<div class="btn-group">' . $btns . $moreBtns . '</div>';
    }
}
