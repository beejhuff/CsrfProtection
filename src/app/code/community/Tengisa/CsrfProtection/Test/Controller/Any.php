<?php
class Tengisa_CsrfProtection_Test_Controller_Any extends EcomDev_PHPUnit_Test_Case_Controller
{
    public function testMe()
    {
        $this->dispatch('');
    }
}
