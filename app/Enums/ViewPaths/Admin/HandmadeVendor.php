<?php

namespace App\Enums\ViewPaths\Admin;

enum HandmadeVendor
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.handmadeseller.index'
    ];

    const ADD = [
        URI => 'add',
        VIEW => 'admin-views.handmadeseller.add-new-seller'
    ];

    const ORDER_LIST = [
        URI => 'order-list',
        VIEW => 'admin-views.handmadeseller.order-list'
    ];

    const ORDER_DETAILS = [
        URI => 'order-details',
        VIEW => 'admin-views.handmadeseller.order-details'
    ];

    const PRODUCT_LIST = [
        URI => 'product-list',
        VIEW => 'admin-views.handmadeseller.product-list'
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

     const DOCSTATUS = [
        URI => 'doc_status',
        VIEW => ''
    ];

    const updateCommissionStatus = [
        URI => 'updateCommissionStatus',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.handmadeseller.view'
    ];

    const VIEW_ORDER = [
        URI => '',
        VIEW => 'admin-views.handmadeseller.view.order'
    ];

    const VIEW_PRODUCT = [
        URI => '',
        VIEW => 'admin-views.handmadeseller.view.product'
    ];

    const VIEW_REVIEW = [
        URI => '',
        VIEW => 'admin-views.handmadeseller.view.review'
    ];

    const VIEW_TRANSACTION = [
        URI => '',
        VIEW => 'admin-views.handmadeseller.view.transaction'
    ];

    const VIEW_SETTING = [
        URI => '',
        VIEW => 'admin-views.handmadeseller.view.setting'
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
        VIEW => 'admin-views.handmadeseller.withdraw'
    ];

    const WITHDRAW_LIST_EXPORT = [
        URI => 'withdraw-list-export-excel',
        VIEW => ''
    ];

    const WITHDRAW_VIEW = [
        URI => 'withdraw-view',
        VIEW => 'admin-views.handmadeseller.withdraw-view',
    ];

    const WITHDRAW_STATUS = [
        URI => 'withdraw-status',
        VIEW => ''
    ];


}
