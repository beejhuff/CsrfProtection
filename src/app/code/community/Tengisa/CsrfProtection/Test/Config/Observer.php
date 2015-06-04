<?php
class Tengisa_CsrfProtection_Test_Config_Observer extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testObserverConfig()
    {
        $this->assertEventObserverDefined('global', 'controller_action_predispatch', 'Tengisa_CsrfProtection_Model_Observer', 'hookToControllerActionPreDispatch');
    }
}
