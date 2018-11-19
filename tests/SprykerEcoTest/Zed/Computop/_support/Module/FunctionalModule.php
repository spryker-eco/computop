<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Computop\Module;

use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

class FunctionalModule extends Module
{
    /**
     * @var \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public $orderEntity;

    /**
     *
     * Requires module container (to provide access between modules of suite) and config.
     *
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param mixed $config
     */
    public function __construct(ModuleContainer $moduleContainer, $config = null)
    {
        parent::__construct($moduleContainer, $config);

        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * **HOOK** executed before test
     *
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        Propel::getWriteConnection('zed')->beginTransaction();

        $this->setUpOrderData();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test)
    {
        parent::_after($test);

        Propel::getWriteConnection('zed')->rollBack();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param \Exception $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail)
    {
        parent::_failed($test, $fail);

        Propel::getWriteConnection('zed')->rollBack();

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
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
