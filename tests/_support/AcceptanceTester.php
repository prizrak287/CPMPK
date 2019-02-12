<?php


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

class AcceptanceTester extends \Codeception\Actor
{

    use _generated\AcceptanceTesterActions;

    public function switchToLastWindow() {
        $this -> executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
            $knobs=$webdriver->getWindowHandles();
            $last_window = end($knobs);
            $webdriver->switchTo()->window($last_window);
        });
    }

    public function switchToParentWindow() {
        $this -> executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
            $webdriver->close();
            $knobs=$webdriver->getWindowHandles();
            $last_window = end($knobs);
            $webdriver->switchTo()->window($last_window);
        });
    }

    public function tryToClick($selector) {
        try
        {
            $this -> click($selector);
        }
        catch (Exception $e)
        {

        }
    }

    public function boolSee($text, $selector = NULL)
    {
        try {
            $this -> see($text, $selector);
            return true;
        } catch (Exception $ex) {
            $this -> dontsee($text, $selector);
            return false;
        }
    }

    public function boolSeeElement($element)
    {
        try {
            $this -> seeElement($element);
            return true;
        } catch (Exception $ex) {
            $this -> dontseeElement($element);
            return false;
        }
    }

    public function boolExistsElement($element)
    {
        try {
            $this -> waitForElement($element, 0.05);
            return true;
        } catch (Exception $ex) {
            $this -> waitForElementNotVisible($element, 0.5);
            return false;
        }
    }

    public function clearField($field)
    {
        $this -> fillField($field, '');
        $this -> seeInField($field, '');
    }

    public function getQuantityElement($element, $start = 1)
    {
        for ($i = $start; ; $i++) {
            if (!$this -> boolExistsElement($element . ':nth-child(' . $i . ')')) {
                return $i - 1;
            }
        }
        return -1;
    }

    public function moveToServer($name)
    {
        $this -> wantTo($name);
        $this -> amOnUrl('http://192.124.187.243:8723');
        $this -> waitForElement('div.auth', 60);
        $this -> waitForElement('button[type="submit"]', 60);
        $this -> wait(1);
    }

    public function inputInSystem($login, $password)
    {
        $this -> waitForElement('div.auth', 60);
        $this -> fillfield('input[id="name"]', $login);
        $this -> wait(1);
        $this -> seeInField('input[id="name"]', $login);
        $this -> fillfield('input[id="password"]', $password);
        $this -> wait(1);
        $this -> seeInField('input[id="password"]', $password);
        $this -> wait(1);
        $this -> click('button[class="button-action enter-button"]');
        $this -> waitForElement('div.header-menu', 60);
        $this -> waitForElement('div.user-block', 60);
    }

    public function exitOfSystem()
    {
        $this -> waitForElement('div.header__right', 10);
        $this -> wait(1);
        $this -> click('div.header-exit', 'div.header__right');
        $this -> waitForElement('div.auth', 60);
    }
}
