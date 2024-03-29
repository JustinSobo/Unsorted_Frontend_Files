<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Copyright (c) 2011-present Qualiteam software Ltd. All rights reserved.
 * See https://www.x-cart.com/license-agreement.html for license details.
 */

namespace XLite\Module\XC\MailChimp;

/**
 * Main module class
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'X-Cart team';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'MailChimp Integration with E-commerce support';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '5.3';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '6';
    }

    /**
     * Get module build number (4th number in the version)
     *
     * @return string
     */
    public static function getBuildVersion()
    {
        return '3';
    }

    /**
     * Get minor core version which is required for the module activation
     *
     * @return string
     */
    public static function getMinorRequiredCoreVersion()
    {
        return '4';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'MailChimp is an email marketing service with powerful tools to manage your email campaigns and'
            . ' subscribers while gathering advanced analytics information on your campaign performance.';
    }

    /**
     * Determines if we need to show settings form link
     *
     * @return boolean
     */
    public static function showSettingsForm()
    {
        return true;
    }

    /**
     * Return link to settings form
     *
     * @return string
     */
    public static function getSettingsForm()
    {
        return \XLite\Core\Converter::buildURL('mailchimp_options');
    }

    public static function updateAllMainStores()
    {
        $stores = static::getMainStores();

        $ecCore = \XLite\Module\XC\MailChimp\Core\MailChimpECommerce::getInstance();

        foreach ($stores as $store) {
            $ecCore->updateStoreAndReference($store->getList()->getId(), true);
        }

        \XLite\Core\Database::getEM()->flush();
    }

    public static function setAllStoreSyncFlag($flagValue)
    {
        $stores = static::getMainStores();

        $ecCore = \XLite\Module\XC\MailChimp\Core\MailChimpECommerce::getInstance();

        foreach ($stores as $store) {
            $ecCore->changeStoreSyncingStatus($store->getId(), $flagValue);
        }
    }

    /**
     * @return \XLite\Module\XC\MailChimp\Model\Store
     */
    public static function getStoreForAbandonedCarts()
    {
        /** @var \XLite\Module\XC\MailChimp\Model\Repo\Store $repo */
        $repo = \XLite\Core\Database::getRepo('XLite\Module\XC\MailChimp\Model\Store');

        return $repo->findStoreByListId(
            \XLite\Core\Config::getInstance()->XC->MailChimp->selectedAbandonedCartsListid
        );
    }

    /**
     * @return \XLite\Module\XC\MailChimp\Model\Store[]
     */
    public static function getMainStores()
    {
        /** @var \XLite\Module\XC\MailChimp\Model\Repo\Store $repo */
        $repo = \XLite\Core\Database::getRepo('XLite\Module\XC\MailChimp\Model\Store');

        return $repo->findBy([
            'main' => true
        ]);
    }

    /**
     * Check if MailChimp module is configured and have lists
     *
     * @return boolean
     */
    public static function isMailChimpConfigured()
    {
        $listsRepo = \XLite\Core\Database::getRepo('\XLite\Module\XC\MailChimp\Model\MailChimpList');

        return \XLite\Module\XC\MailChimp\Core\MailChimp::hasAPIKey()
            && 0 < $listsRepo->countActiveMailChimpLists();
    }

    /**
     * Check if MailChimp module is configured, have lists and have ecommerce enabled
     *
     * @return boolean
     */
    public static function isMailChimpECommerceConfigured()
    {
        return static::isMailChimpConfigured()
            && \XLite\Core\Config::getInstance()->XC->MailChimp->analytics360enabled;
    }

    /**
     * Check if MailChimp module is configured, have lists,
     * have e-commerce enabled and have abandonedCart enabled
     *
     * @return bool
     */
    public static function isMailChimpAbandonedCartEnabled()
    {
        return static::isMailChimpECommerceConfigured()
            && \XLite\Core\Config::getInstance()->XC->MailChimp->abandonedCartEnabled;
    }

    /**
     * @return bool
     */
    public static function isDebugMode()
    {
        return \Includes\Utils\ConfigParser::getOptions(array('log_details', 'level')) === strval(LOG_DEBUG);
    }

    /**
     * Log only in debug mode 
     *
     * @param $message
     * @param $data
     */
    public static function logInfo($message, $data)
    {
        if (static::isDebugMode()) {
            \XLite\Logger::logCustom('mailchimp_info', [
                'message'   => $message,
                'data'      => $data
            ]);
        }
    }

    /**
     * Log always
     *
     * @param $message
     * @param $data
     */
    public static function logError($message, $data)
    {
        \XLite\Logger::logCustom('mailchimp_error', [
            'message'   => $message,
            'data'      => $data
        ]);
    }

    /**
     * @return boolean
     */
    public static function hasGdprRelatedActivity()
    {
        return true;
    }
}
