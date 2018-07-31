<?php

/**
 * Proudly powered by Magentor CLI!
 * Version v0.1.0
 * Official Repository: http://github.com/tiagosampaio/magentor
 *
 * @author Tiago Sampaio <tiago@tiagosampaio.com>
 */

namespace JulioReis\CorreiosFollowup\Model\Tracking;

use JulioReis\CorreiosFollowup\Model\ResourceModel\Tracking\Queue as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Queue extends AbstractModel
{
    const CORREIOS_STATUS_PROCESSING = 'P';
    const CORREIOS_STATUS_CANCELED = 'C';
    const CORREIOS_STATUS_DELIVERED = 'D';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
