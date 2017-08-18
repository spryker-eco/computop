<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Computop;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

abstract class AbstractSetUpTest extends Test
{

    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    protected $orderEntity;

    /**
     * @return void
     */
    abstract protected function createComputopPaymentEntity();

    /**
     * Set up DB data
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->setUpOrderData();
    }

    /**
     * Set up Order data
     *
     * @return void
     */
    protected function setUpOrderData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('DE');
        $billingAddress = new SpySalesOrderAddress();
        $billingAddress->fromArray($this->getAddressTransfer('billing')->toArray());
        $billingAddress->setFkCountry($country->getIdCountry())->save();

        $shippingAddress = new SpySalesOrderAddress();
        $shippingAddress->fromArray($this->getAddressTransfer('shipping')->toArray());
        $shippingAddress->setFkCountry($country->getIdCountry())->save();

        $customer = (new SpyCustomerQuery())
            ->filterByFirstName('John')
            ->filterByLastName('Doe')
            ->filterByEmail('john@doe.com')
            ->filterByDateOfBirth('1970-01-01')
            ->filterByGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->filterByCustomerReference('test')
            ->findOneOrCreate();
        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setOrderReference('TEST--1')
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($shippingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer);

        $this->orderEntity->save();

        $stateEntity = $this->createOrderItemStateEntity();
        $processEntity = $this->createOrderProcessEntity();

        $orderItemEntity = (new SpySalesOrderItem())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setFkOmsOrderItemState($stateEntity->getIdOmsOrderItemState())
            ->setFkOmsOrderProcess($processEntity->getIdOmsOrderProcess())
            ->setName('test product')
            ->setSku('1324354657687980')
            ->setGrossPrice(1000)
            ->setQuantity(1);
        $orderItemEntity->save();

        $this->createComputopPaymentEntity();
    }

    /**
     * @param string $itemPrefix
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressTransfer($itemPrefix)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName($itemPrefix . 'John')
            ->setLastName($itemPrefix . 'Doe')
            ->setCity('Berlin')
            ->setIso2Code('DE')
            ->setAddress1($itemPrefix . 'Straße des 17. Juni')
            ->setAddress2($itemPrefix . '135')
            ->setAddress3($itemPrefix . '135')
            ->setZipCode($itemPrefix . '10623')
            ->setSalutation('Mr')
            ->setPhone($itemPrefix . '12345678');

        return $addressTransfer;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    protected function createOrderItemStateEntity()
    {
        $stateEntity = new SpyOmsOrderItemState();
        $stateEntity->setName('test item state');
        $stateEntity->save();

        return $stateEntity;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    protected function createOrderProcessEntity()
    {
        $processEntity = new SpyOmsOrderProcess();
        $processEntity->setName('test process');
        $processEntity->save();

        return $processEntity;
    }

}
