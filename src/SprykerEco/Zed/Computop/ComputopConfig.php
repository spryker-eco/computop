<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\Computop\ComputopConstants;

class ComputopConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->get(ComputopConstants::COMPUTOP_MERCHANT_ID_KEY);
    }

    /**
     * @return string
     */
    public function getBlowfishPass()
    {
        return $this->get(ComputopConstants::COMPUTOP_BLOWFISH_PASSWORD_KEY);
    }

    /**
     * @return string
     */
    public function getAuthorizeAction()
    {
        return $this->get(ComputopConstants::COMPUTOP_CREDIT_CARD_AUTHORIZE_ACTION_KEY);
    }

    /**
     * @return string
     */
    public function getCaptureAction()
    {
        return $this->get(ComputopConstants::COMPUTOP_CREDIT_CARD_CAPTURE_ACTION_KEY);
    }

    /**
     * @return string
     */
    public function getInquireAction()
    {
        return $this->get(ComputopConstants::COMPUTOP_CREDIT_CARD_INQUIRE_ACTION_KEY);
    }

    /**
     * @return string
     */
    public function getReverseAction()
    {
        return $this->get(ComputopConstants::COMPUTOP_CREDIT_CARD_REVERSE_ACTION_KEY);
    }

}
