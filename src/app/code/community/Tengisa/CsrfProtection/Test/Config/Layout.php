<?php
class Tangent_CsrfProtection_Test_Config_Layout extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testLayoutDefined()
    {
        $this->assertLayoutFileDefined("frontend", "csrfprotection.xml");
    }

    /**
     * @depends testLayoutDefined
     */
    public function testLayoutExists()
    {
        $this->assertLayoutFileExists("frontend", "csrfprotection.xml");
    }
}
