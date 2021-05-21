<?php

namespace SprykerEco\Client\Computop\Resolver;

use Generated\Shared\Transfer\ComputopPayPalExpressPaymentTransfer;

interface ComputopPayPalExpressOrderResolverInterface
{
    public function resolvePayPalCreateOrder(ComputopPayPalExpressPaymentTransfer $computopPayPalExpressPaymentTransfer): ComputopPayPalExpressPaymentResponseTransfer;
}
