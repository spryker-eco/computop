<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Computop\Business\Api\Adapter;

use SprykerEco\Zed\Computop\ComputopConfig;

class AuthorizeApiAdapter extends AbstractApiAdapter
{

    public function __construct(ComputopConfig $config)
    {
        parent::__construct($config);
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function prepareData(array $data)
    {
        //TODO: implement
        return $data;
    }

}
