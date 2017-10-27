<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Service\Computop\Mapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer;
use Generated\Shared\Transfer\ComputopResponseHeaderTransfer;

/**
 * @group Unit
 * @group SprykerEco
 * @group Service
 * @group Computop
 * @group Api
 * @group Mapper
 * @group ComputopMapperTest
 */
class ComputopMapperTestHelper extends Test
{
    /**
     * @return \Generated\Shared\Transfer\ComputopCreditCardPaymentTransfer
     */
    public function createComputopPaymentTransfer()
    {
        $cardPaymentTransfer = new ComputopCreditCardPaymentTransfer();

        $cardPaymentTransfer
            ->setPayId(ComputopMapperTestConstants::PAY_ID_VALUE)
            ->setTransId(ComputopMapperTestConstants::TRANS_ID_VALUE)
            ->setMerchantId(ComputopMapperTestConstants::MERCHANT_ID_VALUE)
            ->setAmount(ComputopMapperTestConstants::AMOUNT_VALUE)
            ->setCurrency(ComputopMapperTestConstants::CURRENCY_VALUE);

        return $cardPaymentTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ComputopResponseHeaderTransfer
     */
    public function createComputopResponseHeaderTransfer()
    {
        $compuropHeaderTransfer = new ComputopResponseHeaderTransfer();

        $compuropHeaderTransfer
            ->setPayId(ComputopMapperTestConstants::PAY_ID_VALUE)
            ->setTransId(ComputopMapperTestConstants::TRANS_ID_VALUE)
            ->setMId(ComputopMapperTestConstants::MERCHANT_ID_VALUE)
            ->setStatus(ComputopMapperTestConstants::STATUS_VALUE)
            ->setCode(ComputopMapperTestConstants::CODE_VALUE);

        return $compuropHeaderTransfer;
    }
}
