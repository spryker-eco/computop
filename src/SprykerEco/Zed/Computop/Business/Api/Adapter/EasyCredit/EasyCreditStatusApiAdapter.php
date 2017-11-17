<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter\EasyCredit;

use SprykerEco\Zed\Computop\Business\Api\Adapter\AbstractApiAdapter;

class EasyCreditStatusApiAdapter extends AbstractApiAdapter
{
    /**
     * @return string
     */
    protected function getUrl()
    {
        return $this->config->getEasyCreditStatusUrl();
    }
}
