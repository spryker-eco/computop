<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Computop;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ComputopConfig extends AbstractBundleConfig
{
    const ETI_ID = '0.0.1'; //Parameter is requested by Computop
    const FINISH_AUTH = 'Y'; //Only with ETM: Transmit value <Y> in order to stop the renewal of guaranteed authorizations and rest amounts after partial captures.
    const RESPONSE_ENCRYPT_TYPE = 'encrypt';
    const TX_TYPE_ORDER = 'Order';
}
