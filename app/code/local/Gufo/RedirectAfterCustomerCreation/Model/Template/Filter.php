<?php

class Gufo_RedirectAfterCustomerCreation_Model_Template_Filter extends Mage_Widget_Model_Template_Filter
{

    /**
     * Return the customer registration url, with the referrer appended,
     * in order to redirect the user at the end of the registration
     *
     * @param array $construction
     * @return string
     */
    public function registerDirective($construction)
    {
        return Mage::helper('customer')->getRegisterUrl();
    }

    /**
     * Return the customer login url, with the referrer appended,
     * in order to redirect the user after the login
     *
     * @param array $construction
     * @return string
     */
    public function loginDirective($construction)
    {
        return Mage::helper('customer')->getLoginUrl();
    }

}