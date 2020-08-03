<?php

declare(strict_types=1);

namespace App\Tests\functional\Game;

use DateTime;
use App\Tests\FunctionalTester;

/**
 * @coversDefaultClass \App\Modules\Product\UI\Admin\Controller\ProductController
 */
class ProductCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->authAdmin($I);
    }

    /**
     * @covers ::lists
     * @param FunctionalTester $I
     */
    public function lists(FunctionalTester $I): void
    {
        $I->click('Products');
        $I->seeInCurrentUrl('/admin/products/lists');
    }

    /**
     * @covers ::create
     * @param FunctionalTester $I
     */
    public function create(FunctionalTester $I): void
    {
        $I->amOnPage('/admin/products/lists');
        $I->click('.btn-success');
        $I->seeInCurrentUrl('/admin/new-product');
        $this->formProduct($I);
    }

    private function formProduct(FunctionalTester $I, array $dataForm = [])
    {
        $data = array_merge(
            [
                'product[name]' => 'Test',
                'product[price]' => 12,
                'product[description]' => '123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce123123123cezce',
            ],
            $dataForm
        );

        $I->submitForm('form[name="product"]', $data);

        $I->seeInCurrentUrl('/admin/products/lists');
    }
}