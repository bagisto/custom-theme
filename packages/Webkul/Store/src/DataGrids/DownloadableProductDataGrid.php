<?php

namespace Webkul\Store\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class DownloadableProductDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('downloadable_link_purchased')
            ->distinct()
            ->leftJoin('orders', 'downloadable_link_purchased.order_id', '=', 'orders.id')
            ->leftJoin('invoices', 'downloadable_link_purchased.order_id', '=', 'invoices.order_id')
            ->addSelect('downloadable_link_purchased.*', 'invoices.state as invoice_state', 'orders.increment_id')
            ->addSelect(DB::raw('(' . DB::getTablePrefix() . 'downloadable_link_purchased.download_bought - ' . DB::getTablePrefix() . 'downloadable_link_purchased.download_canceled - ' . DB::getTablePrefix() . 'downloadable_link_purchased.download_used) as remaining_downloads'))
            ->where('downloadable_link_purchased.customer_id', auth()->guard('customer')->user()->id);

        $this->addFilter('increment_id', 'orders.increment_id');
        $this->addFilter('status', 'downloadable_link_purchased.status');
        $this->addFilter('created_at', 'downloadable_link_purchased.created_at');

        return $queryBuilder;
    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'increment_id',
            'label'      => trans('shop::app.customer.account.downloadable_products.order-id'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('shop::app.customer.account.downloadable_products.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if (
                    $value->status == 'pending'
                    || $value->status == 'expired'
                    || $value->invoice_state !== 'paid'
                ) {
                    return $value->product_name;
                }

                return $value->product_name . ' ' . '<a href="' . route('shop.customer.downloadable_products.download', $value->id) . '" target="_blank">' . $value->name . '</a>';
            },
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('shop::app.customer.account.downloadable_products.date'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('shop::app.customer.account.downloadable_products.status'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($value) {
                if ($value->status == 'pending') {
                    return '<span class="badge badge-md badge-warning">' . trans('shop::app.customer.account.downloadable_products.pending') . '</span>';
                } elseif ($value->status == 'available') {
                    return '<span class="badge badge-md badge-success">' . trans('shop::app.customer.account.downloadable_products.available') . '</span>';
                } elseif ($value->status == 'expired') {
                    return '<span class="badge badge-md badge-danger">' . trans('shop::app.customer.account.downloadable_products.expired') . '</span>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'remaining_downloads',
            'label'      => trans('shop::app.customer.account.downloadable_products.remaining-downloads'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
            'closure'    => function ($value) {
                if (! $value->download_bought) {
                    return trans('shop::app.customer.account.downloadable_products.unlimited');
                }

                return $value->remaining_downloads;
            },
        ]);
    }
}
