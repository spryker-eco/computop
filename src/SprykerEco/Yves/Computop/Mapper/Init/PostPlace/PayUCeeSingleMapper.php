<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayUCeeSingleMapper extends AbstractMapper
{
    protected const SHIPMENT_ARTICLE_NAME = 'Shipment';
    protected const ONE_ITEM_AMOUNT = 1;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPaymentTransfer */
        $computopPaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $computopPaymentTransfer->setMac(
            $this->computopApiService->generateEncryptedMac($this->createRequestTransfer($computopPaymentTransfer))
        );

        $computopPaymentTransfer->setOrderDesc(
            $this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy())
        );

        if ($quoteTransfer->getItems()->count()) {
            $computopPaymentTransfer->setArticleList(
                $this->getArticleList($quoteTransfer->getItems(), $quoteTransfer->getExpenses())
            );
        }

        if ($quoteTransfer->getCustomer()) {
            $this->setCustomerData($computopPaymentTransfer, $quoteTransfer->getCustomer());
        }

        $computopPaymentTransfer->setCapture(
            $this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE)
        );

        $computopPaymentTransfer->setLanguage(mb_strtoupper($this->store->getCurrentLanguage()));

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        $computopPaymentTransfer = new ComputopPayuCeeSinglePaymentTransfer();

        $computopPaymentTransfer->setTransId($this->generateTransId($quoteTransfer));
        $computopPaymentTransfer->setUrlSuccess(
            $this->router->generate(ComputopRouteProviderPlugin::PAYU_CEE_SINGLE_SUCCESS, [], Router::ABSOLUTE_URL)
        );

        return $computopPaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $items
     * @param \Generated\Shared\Transfer\ExpenseTransfer[]|\ArrayObject $expenseList
     *
     * @return string
     */
    private function getArticleList($items, $expenseList): string
    {
        $out = [];

        foreach ($items as $item) {
            $out[] = implode(',', [
                str_replace([',', '+', '-'], ' ', $item->getName()),
                $item->getUnitPrice(),
                $item->getQuantity(),
            ]);
        }

        $out[] = implode(',', [
            static::SHIPMENT_ARTICLE_NAME,
            $this->getDeliveryCosts($expenseList),
            static::ONE_ITEM_AMOUNT,
        ]);

        return implode('+', $out);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[]|\ArrayObject $expenseList
     *
     * @return int
     */
    protected function getDeliveryCosts($expenseList): int
    {
        foreach ($expenseList as $expense) {
            if ($expense->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                return $expense->getSumGrossPrice();
            }
        }

        return 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    private function setCustomerData(ComputopPayuCeeSinglePaymentTransfer $paymentTransfer, CustomerTransfer $customer): void
    {
        $paymentTransfer->setFirstName($customer->getFirstName());
        $paymentTransfer->setLastName($customer->getLastName());
        $paymentTransfer->setEmail($customer->getEmail());
        $paymentTransfer->setPhone($customer->getPhone());
    }
}
