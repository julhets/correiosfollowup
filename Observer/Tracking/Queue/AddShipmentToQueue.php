<?php

namespace JulioReis\CorreiosFollowup\Observer\Tracking\Queue;

use Magento\Framework\Event\Observer;
use JulioReis\CorreiosFollowup\Model\Context as ModuleContext;

class AddShipmentToQueue implements \Magento\Framework\Event\ObserverInterface
{
    protected $context;
    protected $queueFactory;
    protected $queueRepository;

    /**
     * AddShipmentToQueue constructor.
     * @param ModuleContext $context
     * @param \JulioReis\CorreiosFollowup\Model\Tracking\QueueFactory $queueFactory
     * @param \JulioReis\CorreiosFollowup\Model\Tracking\QueueRepository $queueRepository
     */
    public function __construct(
        ModuleContext $context,
        \JulioReis\CorreiosFollowup\Model\Tracking\QueueFactory $queueFactory,
        \JulioReis\CorreiosFollowup\Model\Tracking\QueueRepository $queueRepository
    )
    {
        $this->context = $context;
        $this->queueRepository = $queueRepository;
        $this->queueFactory = $queueFactory;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $track = $observer->getTrack();

        /** get the tracking and save in queue */
        $trackId = $track->getId();
        $queue = $this->queueFactory->create();
        $queue->setCorreiosStatus(\JulioReis\CorreiosFollowup\Model\Tracking\Queue::CORREIOS_STATUS_PROCESSING);
        $queue->setShipmentTrackId($trackId);
        $this->queueRepository->save($queue);
        /** end */
    }
}
