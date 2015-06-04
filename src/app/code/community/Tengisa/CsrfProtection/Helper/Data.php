<?php
class Tengisa_CsrfProtection_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getFormKey()
    {
        return Mage::getSingleton('core/session')->getFormKey();
    }

    public function validateFormKey($formKey)
    {
        if (!$formKey || $formKey != Mage::getSingleton('core/session')->getFormKey())
        {
            return false;
        }
        return true;
    }

    public function redirect($controller)
    {
        
    }
}
