<?php

namespace JulioReis\CorreiosFollowup\Model;

use JulioReis\CorreiosFollowup\Model\Config as ModuleConfig;
use Magento\Framework\App\State;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use JulioReis\CorreiosFollowup\Model\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use JulioReis\CorreiosFollowup\Model\Service as CorreiosService;

class Context implements \Magento\Framework\ObjectManager\ContextInterface
{
    /** @var ModuleConfig */
    protected $moduleConfig;

    /** @var StoreManagerInterface */
    protected $storeManager;

    /** @var ManagerInterface */
    protected $eventManager;

    /** @var ObjectManagerInterface */
    protected $objectManager;

    /** @var LoggerInterface */
    protected $logger;

    /** @var State */
    protected $state;

    /** @var Registry */
    protected $registryManager;

    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var CorreiosService */
    protected $correiosService;

    public function __construct(
        ModuleConfig $moduleConfig,
        StoreManagerInterface $storeManager,
        ManagerInterface $eventManager,
        Logger $logger,
        ObjectManagerInterface $objectManager,
        State $state,
        Registry $registry,
        ScopeConfigInterface $scopeConfig,
        CorreiosService $correiosService
    )
    {
        $this->moduleConfig = $moduleConfig;
        $this->storeManager = $storeManager;
        $this->eventManager = $eventManager;
        $this->objectManager = $objectManager;
        $this->logger = $logger;
        $this->state = $state;
        $this->registryManager = $registry;
        $this->scopeConfig = $scopeConfig;
        $this->correiosService = $correiosService;
    }

    /**
     * @return ManagerInterface
     */
    public function eventManager()
    {
        return $this->eventManager;
    }

    /**
     * @return ObjectManagerInterface
     */
    public function objectManager()
    {
        return $this->objectManager;
    }

    /**
     * @return StoreManagerInterface
     */
    public function storeManager()
    {
        return $this->storeManager;
    }

    /**
     * @return LoggerInterface
     */
    public function logger()
    {
        return $this->logger;
    }

    /**
     * @return State
     */
    public function appState()
    {
        return $this->state;
    }

    public function moduleConfig() {
        return $this->moduleConfig;
    }

    /**
     * @return Registry
     */
    public function registryManager()
    {
        return $this->registryManager;
    }

    /**
     * @return ScopeConfigInterface
     */
    public function scopeConfig()
    {
        return $this->scopeConfig;
    }

    /**
     * @return Service
     */
    public function correiosService() {
        return $this->correiosService;
    }
}
