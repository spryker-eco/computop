<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface;
use SprykerEco\Yves\Computop\Mapper\Order\MapperInterface;

class PayPalFormDataProvider implements StepEngineFormDataProviderInterface
{

    /**
     * @var \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface
     */
    protected $quoteClient;

    /**
     * @var \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface
     */
    protected $cardMapper;

    /**
     * @param \SprykerEco\Yves\Computop\Dependency\Client\ComputopToQuoteInterface $quoteClient
     * @param \SprykerEco\Yves\Computop\Mapper\Order\MapperInterface $cardMapper
     */
    public function __construct(ComputopToQuoteInterface $quoteClient, MapperInterface $cardMapper)
    {
        $this->quoteClient = $quoteClient;
        $this->cardMapper = $cardMapper;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $quoteTransfer->setPayment($paymentTransfer);
        }
        //TODO: check price
        if ($quoteTransfer->getPayment()->getComputopPayPal() === null) {
            $paymentTransfer = $quoteTransfer->getPayment();
            $computopTransfer = $this->cardMapper->createComputopPaymentTransfer($quoteTransfer);
            $paymentTransfer->setComputopPayPal($computopTransfer);
            $quoteTransfer->setPayment($paymentTransfer);

            //TODO: check save Quote to session
            $this->quoteClient->setQuote($quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [];
    }

}