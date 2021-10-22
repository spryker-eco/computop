<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Mapper\Init\PostPlace;

use Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Router\Router\Router;
use SprykerEco\Shared\Computop\ComputopConfig;
use SprykerEco\Shared\Computop\Config\ComputopApiConfig;
use SprykerEco\Yves\Computop\Mapper\Init\AbstractMapper;
use SprykerEco\Yves\Computop\Plugin\Router\ComputopRouteProviderPlugin;

class PaydirektMapper extends AbstractMapper
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ComputopPaydirektPaymentTransfer
     */
    protected function createTransferWithUnencryptedValues(QuoteTransfer $quoteTransfer): ComputopPaydirektPaymentTransfer
    {
        $computopPaymentTransfer = (new ComputopPaydirektPaymentTransfer())
            ->setCapture($this->getCaptureType(ComputopConfig::PAYMENT_METHOD_PAYDIREKT))
            ->setTransId($this->getLimitedTransId($quoteTransfer, ComputopApiConfig::PAYDIRECT_TRANS_ID_LENGTH))
            ->setUrlSuccess($this->router->generate(ComputopRouteProviderPlugin::PAYDIREKT_SUCCESS, [], Router::ABSOLUTE_URL))
            ->setOrderDesc($this->computopApiService->getDescriptionValue($quoteTransfer->getItems()->getArrayCopy()));

        $addressTransfer = $this->getShippingAddressFromQuote($quoteTransfer);

        return $computopPaymentTransfer->setShopApiKey($this->config->getPaydirektShopKey())
            ->setShippingAmount($quoteTransfer->getTotals()->getExpenseTotal())
            ->setShoppingBasketAmount($quoteTransfer->getTotals()->getSubtotal())
            ->setShippingFirstName($addressTransfer->getFirstName())
            ->setShippingLastName($addressTransfer->getLastName())
            ->setShippingZip($addressTransfer->getZipCode())
            ->setShippingCity($addressTransfer->getCity())
            ->setShippingCountryCode($addressTransfer->getIso2Code());
    }
}
