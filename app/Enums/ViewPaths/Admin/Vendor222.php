<?php

namespace App\Enums\ViewPaths\Admin;

enum Vendor
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.seller.index'
    ];

    const ADD = [
        URI => 'add',
        VIEW => 'admin-views.seller.add-new-seller'
    ];

    const ORDER_LIST = [
        URI => 'order-list',
        VIEW => 'admin-views.seller.order-list'
    ];

    const ORDER_DETAILS = [
        URI => 'order-details',
        VIEW => 'admin-views.seller.order-details'
    ];

    const PRODUCT_LIST = [
        URI => 'product-list',
        VIEW => 'admin-views.seller.product-list'
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

     const DOCSTATUS = [
        URI => 'doc_status',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.seller.view'
    ];

    const VIEW_ORDER = [
        URI => '',
        VIEW => 'admin-views.seller.view.order'
    ];

    const VIEW_PRODUCT = [
        URI => '',
        VIEW => 'admin-views.seller.view.product'
    ];

    const VIEW_REVIEW = [
        URI => '',
        VIEW => 'admin-views.seller.view.review'
    ];

    const VIEW_TRANSACTION = [
        URI => '',
        VIEW => 'admin-views.seller.view.transaction'
    ];

    const VIEW_SETTING = [
        URI => '',
        VIEW => 'admin-views.seller.view.setting'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.employee.edit'
    ];
    const UPDATE_SETTING = [
        URI => 'update_setting',
        VIEW => ''
    ];

    const SALES_COMMISSION_UPDATE = [
        URI => 'sales-commission-update',
        VIEW => ''
    ];

    const WITHDRAW_LIST = [
        URI => 'withdraw-list',
        VIEW => 'admin-views.seller.withdraw'
    ];

    const WITHDRAW_LIST_EXPORT = [
        URI => 'withdraw-list-export-excel',
        VIEW => ''
    ];

    const WITHDRAW_VIEW = [
        URI => 'withdraw-view',
        VIEW => 'admin-views.seller.withdraw-view',
    ];

    const WITHDRAW_STATUS = [
        URI => 'withdraw-status',
        VIEW => ''
    ];


}
