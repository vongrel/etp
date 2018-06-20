<?php
namespace Helper;
use Codeception\Util\Shared\Asserts;

// here you can define custom actions
// all public methods declared in helper class will be available in $I


class Acceptance extends \Codeception\Module
{
    /* turn on when deal with modules. This will enable propper work with try-catch
    public function seePageHasElement($element)
    {
        try {
            $this->getModule('WebDriver')->_findElements($element);
        } catch (\PHPUnit_Framework_AssertionFailedError $f) {
            return false;
        }
        return true;
    }
*/
}
