<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Copyright (c) 2011-present Qualiteam software Ltd. All rights reserved.
 * See https://www.x-cart.com/license-agreement.html for license details.
 */

namespace XLite\Module\XC\NotFinishedOrders\Model\Order\Status;

/**
 * Class represents an order
 */
class Shipping extends \XLite\Model\Order\Status\Shipping implements \XLite\Base\IDecorator
{
    const STATUS_NOT_FINISHED = 'NF';

    /**
     * List of change status handlers;
     * top index - old status, second index - new one
     * (<old_status> ----> <new_status>: $statusHandlers[$old][$new])
     *
     * @return array
     */
    public static function getStatusHandlers()
    {
        return array_merge_recursive(parent::getStatusHandlers(), [
            self::STATUS_NOT_FINISHED => [
                self::STATUS_SHIPPED => ['ship'],
            ],
        ]);
    }
}
