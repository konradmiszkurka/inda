<?php
namespace App\Tests;


use LetMeOut\Tests\_generated\FunctionalTesterActions;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class FunctionalTester extends \Codeception\Actor
{
    use FunctionalTesterActions;

    function authAdmin(FunctionalTester $I, array $dataForm = [])
    {
        $I->amOnPage('/login');
        $I->submitForm('#login', array_merge([
            'username' => 'admin',
            'password' => 'admin'
        ], $dataForm));
    }
}
