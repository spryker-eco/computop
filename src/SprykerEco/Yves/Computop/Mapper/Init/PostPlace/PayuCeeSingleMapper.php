<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use ArrayObject;
use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayuCeeSingleMapper extends AbstractMapper
{
    protected const SHIPMENT_ARTICLE_NAME = 'Shipment';
    protected const ONE_ITEM_AMOUNT = 1;
    protected const ARTICLE_LIST_DELIMITER = ',';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    public function createComputopPaymentTransfer(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        /** @var \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer */
        $computopPayuCeeSinglePaymentTransfer = parent::createComputopPaymentTransfer($quoteTransfer);

        $encryptedMac = $this->computopApiService->generateEncryptedMac(
            $this->createRequestTransfer($computopPayuCeeSinglePaymentTransfer)
        );

        $computopPayuCeeSinglePaymentTransfer
            ->setMac($encryptedMac)
            ->setOrderDesc($this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy()))
            ->setCapture($this->getCaptureType(ComputopSharedConfig::PAYMENT_METHOD_PAYU_CEE_SINGLE))
            ->setLanguage(mb_strtoupper($this->store->getCurrentLanguage()));

        if ($quoteTransfer->getItems()->count()) {
            $computopPayuCeeSinglePaymentTransfer->setArticleList(
                $this->getArticleList($quoteTransfer->getItems(), $quoteTransfer->getExpenses())
            );
        }

        if ($quoteTransfer->getCustomer()) {
            $computopPayuCeeSinglePaymentTransfer->fromArray($quoteTransfer->getCustomer()->toArray(), true);
        }

        return $computopPayuCeeSinglePaymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPayuCeeSinglePaymentTransfer
    {
        $urlSuccess = $this->router->generate(ComputopRouteProviderPlugin::PAYU_CEE_SINGLE_SUCCESS, [], Router::ABSOLUTE_URL);

        return (new ComputopPayuCeeSinglePaymentTransfer())
            ->setTransId($this->generateTransId($quoteTransfer))
            ->setUrlSuccess($urlSuccess);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject $itemTransfers
     * @param \Generated\Shared\Transfer\ExpenseTransfer[]|\ArrayObject $expenseTransfers
     *
     * @return string
     */
    protected function getArticleList(ArrayObject $itemTransfers, ArrayObject $expenseTransfers): string
    {
        $articlesList = [];

        foreach ($itemTransfers as $item) {
            $articlesList[] = implode(static::ARTICLE_LIST_DELIMITER, [
                $item->getName() ? $this->replaceForbiddenCharacters($item->getName()) : '',
                $item->getSumSubtotalAggregation(),
                $item->getQuantity(),
            ]);
        }

        $articlesList[] = implode(static::ARTICLE_LIST_DELIMITER, [
            static::SHIPMENT_ARTICLE_NAME,
            $this->getDeliveryCosts($expenseTransfers),
            static::ONE_ITEM_AMOUNT,
        ]);

        return implode('+', $articlesList);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer[]|\ArrayObject $expenseTransfers
     *
     * @return int
     */
    protected function getDeliveryCosts(ArrayObject $expenseTransfers): int
    {
        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                return $expenseTransfer->getSumGrossPrice();
            }
        }

        return 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer
     */
    protected function setCustomerData(
        ComputopPayuCeeSinglePaymentTransfer $computopPayuCeeSinglePaymentTransfer,
        CustomerTransfer $customerTransfer
    ): ComputopPayuCeeSinglePaymentTransfer {
        return $computopPayuCeeSinglePaymentTransfer
            ->setFirstName($customerTransfer->getFirstName())
            ->setLastName($customerTransfer->getLastName())
            ->setEmail($customerTransfer->getEmail())
            ->setPhone($customerTransfer->getPhone());
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function replaceForbiddenCharacters(string $name): string
    {
        return str_replace([',', '+', '-'], ' ', $name);
    }
}
