<?php
class Tengisa_CsrfProtection_Test_Controller_Any extends EcomDev_PHPUnit_Test_Case_Controller
{
    public function setUp()
    {
        $this->reset();
        Mage::getSingleton('core/session')->setData('messages', null);
    }

    public function testCsrfTemplateAdded()
    {
        $this->dispatch('contacts/');

        $this->assertLayoutLoaded();
        $this->assertLayoutBlockActionInvoked("head", "addItem", $arguments = array("type" => "skin_js", "name" => "js/csrf-protection.js"));
        $this->assertLayoutBlockCreated("csrfprotection");
        $this->assertLayoutBlockTypeOf("csrfprotection", "core/template");
        $this->assertLayoutBlockRendered("csrfprotection");
    }

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
        $this->getRequest()->resetHeaders()->setHeader('Referer', Mage::getUrl('contacts/'));

        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->once())
                ->method('validateFormKey')
                ->will($this->returnCallback(
                    array($this, 'invalidFormKeyCallback')
                ));

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->getRequest()->setMethod('POST');
        $this->getResponse()->reset();

        $this->dispatch('contacts/index/post/');

        $this->assertRedirect();
        $this->assertRedirectTo('contacts/');
        $messages = Mage::getSingleton('core/session')->getMessages()->getItems();
        $this->assertNotEmpty($messages);
        $this->assertEquals(Mage::helper('core')->__("Invalid Form Key. Please refresh the page."), $messages[0]->getCode());
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

        $this->assertRedirect();
        $this->assertRedirectTo('contacts/index/');
    }

    /**
    * @depends testPostMethod
    */
    public function testAjaxPostInvalidKey()
    {
        $this->getRequest()->setQuery('ajax', 'true');

        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->once())
                ->method('validateFormKey')
                ->will($this->returnCallback(
                    array($this, 'invalidFormKeyCallback')
                ));

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->getRequest()->setMethod('POST');
        $this->getResponse()->reset();

        $this->dispatch('contacts/index/post/');

        $this->assertResponseHttpCode(403);
        $this->assertResponseBodyJson();
        $this->assertResponseBodyContains('"error":true');
        $this->assertResponseBodyContains(Mage::helper('core')->__("Invalid Form Key. Please refresh the page."));
    }

    /**
    * @depends testPostMethod
    */
    public function testAjaxPostValidKey()
    {
        $this->getRequest()->setQuery('ajax', 'true');

        $helperMock = $this->getHelperMock('csrfprotection/data');
        $helperMock->expects($this->once())
                ->method('validateFormKey')
                ->will($this->returnCallback(
                    array($this, 'validFormKeyCallback')
                ));

        $this->replaceByMock('helper', 'csrfprotection/data', $helperMock);

        $this->getRequest()->setMethod('POST');
        $this->getResponse()->reset();

        $this->dispatch('contacts/index/post/');

        $this->assertRedirect();
        $this->assertRedirectTo('contacts/index/');
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
