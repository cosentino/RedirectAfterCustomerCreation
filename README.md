# RedirectAfterCustomerCreation

=============

Magento Modules that redirects the customer to the previous url after the registration is completed successfully.

## Setup

In order to get the header link "Sign Up" to take into account the referer for the redirect,
you also need to modify the following file in your template:

/app/design/frontend/default/<templatefolder>/template/page/template/links.phtml

replacing the sign up <li>...</li> with the following:

<li><a href="<?php echo Mage::Helper('customer')->getRegisterUrl() ?>"><?php echo $this->__('Sign Up') ?></a></li>

In case your template does not have this file, you should clone it from the default template:

/app/design/frontend/default/default/template/page/template/links.phtml

## Customs CMS directives

This module provides you two additional CMS directives you can use inside CMS pages and CMS blocks:
- {{register}} : generate the url to the customer registration page together with the referer parameter to the current page
- {{login}} : generate the url to the login page together with the referer parameter to the current page
