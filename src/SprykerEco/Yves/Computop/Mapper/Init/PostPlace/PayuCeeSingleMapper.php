<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use ArrayObject;
use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig as ComputopSharedConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PayuCeeSingleMapper extends AbstractMapper
{
    /**
     * @var string
     */
    protected const SHIPMENT_ARTICLE_NAME = 'Shipment';

    /**
     * @var int
     */
    protected const ONE_ITEM_AMOUNT = 1;

    /**
     * @var string
     */
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
                $this->getArticleList($quoteTransfer)
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getArticleList(QuoteTransfer $quoteTransfer): string
    {
        $articlesList = [];

        foreach ($quoteTransfer->getItems() as $item) {
            $articlesList[] = implode(static::ARTICLE_LIST_DELIMITER, [
                $item->getName() ? $this->replaceForbiddenCharacters($item->getName()) : '',
                $item->getUnitPriceToPayAggregation(),
                $item->getQuantity(),
            ]);
        }

        $articlesList[] = implode(static::ARTICLE_LIST_DELIMITER, [
            static::SHIPMENT_ARTICLE_NAME,
            $this->getDeliveryCosts($quoteTransfer->getExpenses()),
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
        $deliveryCosts = 0;
        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                $deliveryCosts += $expenseTransfer->getSumPriceToPayAggregation();
            }
        }

        return $deliveryCosts;
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
