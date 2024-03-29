<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Copyright (c) 2011-present Qualiteam software Ltd. All rights reserved.
 * See https://www.x-cart.com/license-agreement.html for license details.
 */

namespace XLite\Model\Order\Status;

/**
 * Payment status
 *
 *  Entity
 *  Table  (name="order_payment_statuses",
 *      indexes={
 *           Index (name="code", columns={"code"})
 *      }
 * )
 */
abstract class PaymentAbstract extends \XLite\Model\Order\Status\AStatus
{
    /**
     * Statuses
     */
    const STATUS_AUTHORIZED     = 'A';
    const STATUS_PART_PAID      = 'PP';
    const STATUS_PAID           = 'P';
    const STATUS_DECLINED       = 'D';
    const STATUS_CANCELED       = 'C';
    const STATUS_QUEUED         = 'Q';
    const STATUS_REFUNDED       = 'R';

    /**
     * Disallowed to set manually statuses
     *
     * @return array
     */
    public static function getDisallowedToSetManuallyStatuses()
    {
        return [
            self::STATUS_AUTHORIZED,
            self::STATUS_DECLINED
        ];
    }

    /**
     * Not compatible with Shipping statuses
     *
     * @return array
     */
    public static function getNotCompatibleWithShippingStatuses()
    {
        return [
            self::STATUS_DECLINED,
            self::STATUS_CANCELED,
        ];
    }

    /**
     * Get open order statuses
     *
     * @return array
     */
    public static function getOpenStatuses()
    {
        return [
            static::STATUS_AUTHORIZED,
            static::STATUS_PART_PAID,
            static::STATUS_PAID,
            static::STATUS_QUEUED,
            static::STATUS_REFUNDED,
        ];
    }

    /**
     * Get paid statuses
     *
     * @return array
     */
    public static function getPaidStatuses()
    {
        return [
            static::STATUS_AUTHORIZED,
            static::STATUS_PART_PAID,
            static::STATUS_PAID,
        ];
    }

    /**
     * Payment status is compatible with shipping status
     *
     * @return boolean
     */
    public function isCompatibleWithShippingStatus()
    {
        return !in_array(
            $this->getCode(),
            static::getNotCompatibleWithShippingStatuses()
        );
    }

    /**
     * Status is allowed to set manually
     *
     * @return boolean
     */
    public function isAllowedToSetManually()
    {
        return !in_array(
            $this->getCode(),
            static::getDisallowedToSetManuallyStatuses()
        );
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Payment
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Payment
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * List of change status handlers;
     * top index - old status, second index - new one
     * (<old_status> ----> <new_status>: $statusHandlers[$old][$new])
     *
     * @return array
     */
    public static function getStatusHandlers()
    {
        return [
            self::STATUS_QUEUED => [
                self::STATUS_AUTHORIZED => ['authorize'],
                self::STATUS_PAID       => ['process'],
                self::STATUS_DECLINED   => ['decline', 'uncheckout', 'fail'],
                self::STATUS_CANCELED   => ['decline', 'uncheckout', 'cancel'],
            ],

            self::STATUS_AUTHORIZED => [
                self::STATUS_PAID       => ['process'],
                self::STATUS_DECLINED   => ['decline', 'uncheckout', 'fail'],
                self::STATUS_CANCELED   => ['decline', 'uncheckout', 'cancel'],
            ],

            self::STATUS_PART_PAID => [
                self::STATUS_PAID       => ['process'],
                self::STATUS_DECLINED   => ['decline', 'uncheckout', 'fail'],
                self::STATUS_CANCELED   => ['decline', 'uncheckout', 'fail'],
            ],

            self::STATUS_PAID => [
                self::STATUS_DECLINED   => ['decline', 'uncheckout', 'fail'],
                self::STATUS_CANCELED   => ['decline', 'uncheckout', 'cancel'],
            ],

            self::STATUS_DECLINED => [
                self::STATUS_AUTHORIZED => ['checkout', 'queue', 'authorize'],
                self::STATUS_PART_PAID  => ['checkout', 'queue'],
                self::STATUS_PAID       => ['checkout', 'queue', 'process'],
                self::STATUS_QUEUED     => ['checkout', 'queue'],
                self::STATUS_CANCELED   => ['cancel'],
            ],

            self::STATUS_CANCELED => [
                self::STATUS_AUTHORIZED => ['checkout', 'queue', 'authorize'],
                self::STATUS_PART_PAID  => ['checkout', 'queue'],
                self::STATUS_PAID       => ['checkout', 'queue', 'process'],
                self::STATUS_QUEUED     => ['checkout', 'queue'],
                self::STATUS_DECLINED   => ['fail'],
            ],

            self::STATUS_REFUNDED => [
                self::STATUS_PAID       => ['process'],
                self::STATUS_DECLINED   => ['fail'],
                self::STATUS_CANCELED   => ['cancel'],
            ],
        ];
    }
}
