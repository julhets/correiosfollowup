<?php
/**
 * JulioReis_CorreiosFollowup
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  JulioReis
 * @package   JulioReis_CorreiosFollowup
 *
 * @copyright Copyright (c) 2018 Julio Reis (www.rapidets.com.br)
 *
 * @author    Julio Reis <julioreis.si@gmail.com>
 */

namespace JulioReis\CorreiosFollowup\Model\Tracking;

use JulioReis\CorreiosFollowup\Model\ResourceModel\Tracking\Queue\CollectionFactory as QueueCollectionFactory;
use JulioReis\CorreiosFollowup\Model\ResourceModel\Tracking\QueueFactory as QueueResourceFactory;
use JulioReis\CorreiosFollowup\Model\Tracking\Queue as Queue;
use JulioReis\CorreiosFollowup\Model\Context as ModuleContext;

class QueueRepository
{

    /** @var QueueFactory */
    protected $queueFactory;

    /** @var QueueCollectionFactory */
    protected $queueCollectionFactory;

    /** @var QueueResourceFactory */
    protected $queueResourceFactory;

    /** @var ModuleContext */
    protected $context;

    /**
     * QueueRepository constructor.
     * @param QueueFactory $queueFactory
     * @param QueueCollectionFactory $queueCollectionFactory
     * @param QueueResourceFactory $queueResourceFactory
     * @param ModuleContext $context
     */
    public function __construct(
        QueueFactory $queueFactory,
        QueueCollectionFactory $queueCollectionFactory,
        QueueResourceFactory $queueResourceFactory,
        ModuleContext $context
    )
    {
        $this->queueFactory = $queueFactory;
        $this->queueCollectionFactory = $queueCollectionFactory;
        $this->queueResourceFactory = $queueResourceFactory;
        $this->context = $context;
    }

    /**
     * @param $shipmentTrackId string
     * @return \JulioReis\CorreiosFollowup\Model\Tracking\Queue
     */
    public function loadByShipmentTrackId($shipmentTrackId)
    {
        $queue = $this->queueFactory->create();
        $this->getResource()->load($queue, $shipmentTrackId, 'shipment_track_id');
        return $queue;
    }

    /**
     * @param Queue $queue
     * @return Queue
     */
    public function save(Queue $queue)
    {
        if (!$queue->getId()) {
            $queue->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $queue->setUpdatedAt(date('Y-m-d H:i:s'));
        $this->getResource()->save($queue);
        return $queue;
    }

    /**
     * @param Queue $queue
     * @return Queue
     */
    public function delete(Queue $queue)
    {
        $this->getResource()->delete($queue);
        return $queue;
    }

    /**
     * @return \JulioReis\CorreiosFollowup\Model\ResourceModel\Tracking\Queue\Collection
     */
    public function getCollection()
    {
        return $this->queueCollectionFactory->create();
    }

    /**
     * @return \JulioReis\CorreiosFollowup\Model\ResourceModel\Tracking\Queue\Collection
     */
    public function getPendingTracks()
    {
        $collection = $this->getCollection();
        $collection->addFieldToFilter('correios_status',
            [
                'in' => [
                    \JulioReis\CorreiosFollowup\Model\Tracking\Queue::CORREIOS_STATUS_PROCESSING
                ]
            ]
        );

        if ($daysQtyToExpire = $this->context->moduleConfig()->getModuleConfig('days_to_expire')) {
            if (is_numeric($daysQtyToExpire)) {
                $date = date('Y-m-d', strtotime("-{$daysQtyToExpire} day"));
                $collection->addFieldToFilter('created_at',
                    [
                        'gt' => $date
                    ]
                );
            }
        }
        return $collection;
    }

    /**
     * @return \JulioReis\CorreiosFollowup\Model\ResourceModel\Tracking\Queue
     */
    protected function getResource()
    {
        return $this->queueResourceFactory->create();
    }
}
