<?php
/**
 * All publicly accessible method names correspond to the event they observe.
 *
 * @category    Gufo
 * @package     Gufo_RedirectAfterCustomerCreation
 */
class Gufo_RedirectAfterCustomerCreation_Model_Observer_Frontend
{

    /**
     * @param Varien_Event_Observer $observer
     */
    public function customerRegisterSuccess($observer)
    {
        /* @var $session Mage_Customer_Model_Session */
        $session = Mage::getSingleton('customer/session');

        // This event occurs within Mage_Customer_AccountController::createPostAction
        // however it occurs before the controller sets it's own redirect settings.
        // Therefore we set this flag to true now, and then within the postdispatch
        // we'll redirect to our custom URL
        $session->setData('customer_register_success', true);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function controllerActionPostdispatchCreatePostAction($observer)
    {
        /* @var $controller Mage_Customer_AccountController */
        /* @var $session Mage_Customer_Model_Session */

        $customerSession = Mage::getSingleton('customer/session');
        $coreSession = Mage::getSingleton('core/session');

        // We must test for a successful customer registration because they
        // could have failed registration despite reaching postdispatch
        // (for example: they used an existing email address)
        if ($customerSession->getData('customer_register_success')) {
            $customerSession->unsetData('customer_register_success');

            // rewrite the message to the "correct" session
            // in order to make sure that any page will be able to show the message
            // (see http://blog.fabian-blechschmidt.de/messages-in-magento-only-loaded-by-default-from-core-session/)
            foreach ($customerSession->getMessages()->getItems() as $message) {
                $coreSession->addMessage($message);
            }
            $customerSession->getMessages(true);

            // Redirect customer to the last page visited before registering
            $controller = $observer->getData('controller_action');
            $referer = $controller->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME);
            if ($referer) {
                // Rebuild referer URL to handle the case when SID was changed
                $referer = Mage::getModel('core/url')
                    ->getRebuiltUrl(Mage::helper('core')->urlDecode($referer));
            }
            $controller->getResponse()->setRedirect($referer);
        }
    }

}