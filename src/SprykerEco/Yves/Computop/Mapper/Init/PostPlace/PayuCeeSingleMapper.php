<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPayuCeeSinglePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @var string
     */
    protected const ROUND_ARTICLE_NAME = 'Rounded';

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
        $totalSum = 0;
        $articlesList = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $unitPrice = (int)round(($itemTransfer->getSumPriceToPayAggregation() - $itemTransfer->getCanceledAmount()) / $itemTransfer->getQuantity());
            $itemName = $itemTransfer->getName() ? $this->replaceForbiddenCharacters($itemTransfer->getName()) : '';
            $totalSum += $unitPrice * $itemTransfer->getQuantity();
            $articlesList[] = $this->getArticleItem($itemName, $unitPrice, $itemTransfer->getQuantity());
        }

        foreach ($quoteTransfer->getExpenses() as $expenseTransfer) {
            $expensePrice = (int)($expenseTransfer->getSumPriceToPayAggregation() - $expenseTransfer->getCanceledAmount());
            $totalSum += $expensePrice;
            $articlesList[] = $this->getArticleItem($expenseTransfer->getName(), $expensePrice);
        }

        $grandSumDifference = (int)$quoteTransfer->getTotals()->getGrandTotal() - (int)$totalSum;
        if ($grandSumDifference !== 0) {
            $articlesList[] = $this->getArticleItem(static::ROUND_ARTICLE_NAME, $grandSumDifference);
        }

        return implode('+', $articlesList);
    }

    /**
     * @param string $name
     * @param int $price
     * @param int $quantity
     *
     * @return string
     */
    protected function getArticleItem(string $name, int $price, int $quantity = 1): string
    {
        return implode(static::ARTICLE_LIST_DELIMITER, [$name, $price, $quantity]);
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
