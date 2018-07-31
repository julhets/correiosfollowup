<?php

/**
 * Proudly powered by Magentor CLI!
 * Version v0.1.0
 * Official Repository: http://github.com/tiagosampaio/magentor
 *
 * @author Tiago Sampaio <tiago@tiagosampaio.com>
 */

namespace JulioReis\CorreiosFollowup\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order;

class InstallData implements InstallDataInterface
{
    use SetupHelper;

    /**
     * @inheritdoc
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->setup = $setup;
        $this->setup()->startSetup();

        $this->createAssociatedSalesOrderStatuses($this->getStatuses());

        $this->setup()->endSetup();
    }

    /**
     * @param array $states
     *
     * @return $this
     */
    public function createAssociatedSalesOrderStatuses(array $states = [])
    {
        foreach ($states as $stateCode => $statuses) {
            $this->createSalesOrderStatus($stateCode, $statuses);
        }

        return $this;
    }


    /**
     * @param string $state
     * @param array $status
     *
     * @return $this
     */
    public function createSalesOrderStatus($state, array $status)
    {
        foreach ($status as $statusCode => $statusLabel) {
            $statusData = [
                'status' => $statusCode,
                'label' => $statusLabel
            ];

            $this->getConnection()->insertOnDuplicate($this->getSalesOrderStatusTable(), $statusData, [
                'status', 'label'
            ]);

            $this->associateStatusToState($state, $statusCode);
        }

        return $this;
    }


    /**
     * @param string $state
     * @param string $status
     * @param int $isDefault
     *
     * @return $this
     */
    public function associateStatusToState($state, $status, $isDefault = 0)
    {
        $associationData = [
            'status' => (string)$status,
            'state' => (string)$state,
            'is_default' => (int)$isDefault,
        ];

        $this->getConnection()
            ->insertOnDuplicate($this->getSalesOrderStatusStateTable(), $associationData, [
                'status',
                'state',
                'is_default',
            ]);

        return $this;
    }

    /**
     * @return array
     */
    protected function getStatuses()
    {
        $statuses = [
            Order::STATE_COMPLETE => [
                'customer_delivered' => 'Delivered to Customer'
            ]
        ];

        return $statuses;
    }


    /**
     * @return string
     */
    protected function getSalesOrderStatusTable()
    {
        return $this->getTable('sales_order_status');
    }


    /**
     * @return string
     */
    protected function getSalesOrderStatusStateTable()
    {
        return $this->getTable('sales_order_status_state');
    }
}
