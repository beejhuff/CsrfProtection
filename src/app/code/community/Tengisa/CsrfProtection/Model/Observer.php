<?php
class Tengisa_CsrfProtection_Model_Observer extends Varien_Event_Observer
{
    public function hookToControllerActionPreDispatch($observer)
    {
        $action = $observer->getEvent()->getControllerAction();
        $request = $action->getRequest();
        if ($request->isPost())
        {
            $key = $request->getParam('form_key', null);
            if (!$key)
            {
                $key = $request->getHeader('X-CSRF-Token', null);
            }
            $helper = Mage::helper('csrfprotection/data');
            if (!$helper->validateFormKey($key))
            {
                $_keyErrorMsg = Mage::helper('core')->__('Invalid Form Key. Please refresh the page.');

                if ($action->getRequest()->getQuery('isAjax', false) || $action->getRequest()->getQuery('ajax', false)) {
                    $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                    $action->getResponse()->setHttpResponseCode(403);
                    $action->getResponse()->setBody(Mage::helper('core')->jsonEncode(array(
                        'error' => true,
                        'message' => $_keyErrorMsg
                    )));
                } else {
                    $action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                    Mage::getSingleton('core/session')->addError($_keyErrorMsg);
                    if (isset($_SERVER['HTTP_REFERER']))
                    {
                        $action->getResponse()->setRedirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }
        }
    }
}
