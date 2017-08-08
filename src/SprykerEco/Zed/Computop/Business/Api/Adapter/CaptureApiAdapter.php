<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

use Spryker\Shared\Config\Config;
use SprykerEco\Shared\Computop\ComputopConstants;

class CaptureApiAdapter extends AbstractApiAdapter
{

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepareData(array $data)
    {
        return $data;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return Config::get(ComputopConstants::COMPUTOP_CREDIT_CARD_CAPTURE_ACTION_KEY);
    }

}
