<?php

namespace FS\MultiCouponsInsertion\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

class Coupon extends XFCP_Coupon
{

    /** @var array */
    protected $dataInput;

    /**
     * @return \DBTech\eCommerce\Service\Coupon\Create
     * @throws \Exception
     * @throws \Exception
     * @throws \Exception
     */
    protected function setupCouponCreate(): \DBTech\eCommerce\Service\Coupon\Create
    {
        $bulkInput = $this->dataInput;
        /** @var \DBTech\eCommerce\Service\Coupon\Create $creator */
        $creator = $this->service('DBTech\eCommerce:Coupon\Create');

        $creator->getCoupon()->bulkSet($bulkInput);

        $creator->setTitle($this->filter('title', 'str'));

        $dateInput = $this->filter([
            'start_date' => 'datetime',
            'start_time' => 'str'
        ]);
        $creator->setStartDate($dateInput['start_date'], $dateInput['start_time']);

        $dateInput = $this->filter([
            'length_amount' => 'uint',
            'length_unit' => 'str',
        ]);
        $creator->setDuration($dateInput['length_amount'], $dateInput['length_unit']);

        $discounts = [];
        $args = $this->filter('product_discounts', 'array');
        foreach ($args as $arg) {
            if (empty($arg['product_id'])) {
                continue;
            }
            $discounts[] = $this->filterArray($arg, [
                'product_id' => 'uint',
                'product_value' => 'float',
            ]);
        }

        $creator->setProductDiscounts($discounts);

        return $creator;
    }


    /**
     * @param ParameterBag $params
     *
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect
     * @throws \LogicException
     * @throws \XF\Mvc\Reply\Exception
     * @throws \Exception
     */
    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params->coupon_id) {
            /** @var \DBTech\eCommerce\Entity\Coupon $coupon */
            $coupon = $this->assertCouponExists($params->coupon_id);

            /** @var \DBTech\eCommerce\Service\Coupon\Edit $editor */
            $editor = $this->setupCouponEdit($coupon);
            //			$editor->checkForSpam();

            if (!$editor->validate($errors)) {
                return $this->error($errors);
            }
            $editor->save();
            $this->finalizeCouponEdit($editor);
        } else {

            $formInput = $this->formInputsData();

            $couponsArray = explode(PHP_EOL, $formInput['multi_coupons_code']);

            foreach ($couponsArray as $value) {

                $this->setCouponData($value);

                /** @var \DBTech\eCommerce\Service\Coupon\Create $creator */
                $creator = $this->setupCouponCreate();
                //			$creator->checkForSpam();

                if (!$creator->validate($errors)) {
                    throw $this->exception($this->error($errors));
                }

                /** @var \DBTech\eCommerce\Entity\Coupon $coupon */
                $coupon = $creator->save();
                $this->finalizeCouponCreate($creator);
            }
        }


        return $this->redirect($this->buildLink('dbtech-ecommerce/coupons') . $this->buildLinkHash($coupon->coupon_id));
    }


    protected function formInputsData()
    {
        return $this->filter([
            'multi_coupons_code' => 'str',
            'coupon_type' => 'str',
            'coupon_percent' => 'float',
            'coupon_value' => 'float',
            'discount_excluded' => 'bool',
            'allow_auto_discount' => 'bool',
            'remaining_uses' => 'int',
            'minimum_products' => 'uint',
            'maximum_products' => 'uint',
            'minimum_cart_value' => 'float',
            'maximum_cart_value' => 'float',
        ]);
    }

    protected function setCouponData($value)
    {

        $formInput = $this->formInputsData();

        $this->dataInput = [
            'coupon_code' => $value,
            'coupon_type' => $formInput['coupon_type'],
            'coupon_percent' => $formInput['coupon_percent'],
            'coupon_value' => $formInput['coupon_value'],
            'discount_excluded' => $formInput['discount_excluded'],
            'allow_auto_discount' => $formInput['allow_auto_discount'],
            'remaining_uses' => $formInput['remaining_uses'],
            'minimum_products' => $formInput['minimum_products'],
            'maximum_products' => $formInput['maximum_products'],
            'minimum_cart_value' => $formInput['minimum_cart_value'],
            'maximum_cart_value' => $formInput['maximum_cart_value'],
        ];
    }
}
