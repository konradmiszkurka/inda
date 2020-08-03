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
        $I->seeInCurrentUrl('/admin/product/new-product');
        $this->formProduct();
    }

    /**
     * @covers ::edit
     * @param FunctionalTester $I
     */
    public function edit(FunctionalTester $I): void
    {
        $I->amOnPage('/game/create');
        $id = $this->formInstitution($I)['id'];
        $I->seeInCurrentUrl('/game/' . $id . '/edit');

        $this->formInstitution($I);
        $I->seeInCurrentUrl('/game/lists');
    }

    private function formProduct(FunctionalTester $I, array $dataForm = []): array
    {
        $uniq = uniqid('', true);
        $data = array_merge(
            [
                'name' => 'Test',
                'price' => 12,
                'Description' => '123123123cezce',
            ],
            $dataForm
        );

        $I->submitForm('form[name="product"]', $data);

        preg_match_all('/[0-9]+/', $I->grabFromCurrentUrl(), $matches);
        return [
            'id' => $matches[0][0] ?? null,
        ];
    }
}