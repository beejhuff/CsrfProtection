<?php
class Tengisa_CsrfProtection_Test_Controller_Any extends EcomDev_PHPUnit_Test_Case_Controller
{
    public function testGetMethod()
    {
        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->never())
                ->method('validateFormKey');

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->dispatch('contacts/');
    }

    public function testPostMethod()
    {
        $key = Mage::getSingleton('core/session')->getFormKey();

        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->once())
                ->method('validateFormKey')
                ->with($key);

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->getRequest()->setMethod('POST');

        $this->dispatch('contacts/index/post/');
    }

    /**
    * @depends testPostMethod
    */
    public function testPostInvalidKey()
    {
        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->once())
                ->method('validateFormKey')
                ->will($this->returnCallback(
                    array($this, 'invalidFormKeyCallback')
                ));

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->getRequest()->setMethod('POST');

        $this->dispatch('contacts/index/post/');

        var_dump('todo: assert failed');
        // todo: assert that request has stopped and that error message has been set
    }

    /**
    * @depends testPostMethod
    */
    public function testPostValidKey()
    {
        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->once())
                ->method('validateFormKey')
                ->will($this->returnCallback(
                    array($this, 'validFormKeyCallback')
                ));

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->getRequest()->setMethod('POST');

        $this->dispatch('contacts/index/post/');

        var_dump('todo: assert success');
        // todo: assert that request has succeeded and that success message has been set
    }

    /**
    * @depends testPostMethod
    */
    public function testAjaxPostInvalidKey()
    {
    }

    /**
    * @depends testPostMethod
    */
    public function testAjaxPostValidKey()
    {

    }

    function invalidFormKeyCallback()
    {
        return false;
    }

    function validFormKeyCallback()
    {
        return true;
    }
}
