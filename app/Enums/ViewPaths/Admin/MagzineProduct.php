<?php

namespace App\Enums\ViewPaths\Admin;

enum MagzineProduct
{
    const ADD = [
        URI => 'add',
        VIEW => 'admin-views.magzineproduct.add-new'
    ];

    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.magzineproduct.list'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.magzineproduct.edit',
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.magzineproduct.view',
        ROUTE => 'admin.magzineproducts.view'
    ];

    const SKU_COMBINATION = [
        URI => 'sku-combination',
        VIEW => 'admin-views.magzineproduct.partials._sku_combinations'
    ];

    const FEATURED_STATUS = [
        URI => 'featured-status',
        VIEW => ''
    ];

    const UPDATE_STATUS = [
        URI => 'status-update',
        VIEW => ''
    ];

    const GET_CATEGORIES = [
        URI => 'get-categories',
        VIEW => ''
    ];

    const BARCODE_VIEW = [
        URI => 'barcode',
        VIEW => 'admin-views.magzineproduct.barcode'
    ];

    const BARCODE_GENERATE = [
        URI => 'barcode',
        VIEW => 'admin-views.magzineproduct.barcode'
    ];

    const EXPORT_EXCEL = [
        URI => 'export-excel',
        VIEW => ''
    ];

    const STOCK_LIMIT = [
        URI => 'stock-limit-list',
        VIEW => 'admin-views.magzineproduct.stock-limit-list'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const DELETE_IMAGE = [
        URI => 'delete-image',
        VIEW => ''
    ];

    const GET_VARIATIONS = [
        URI => 'get-variations',
        VIEW => 'admin-views.magzineproduct.partials._update_stock'
    ];

    const UPDATE_QUANTITY = [
        URI => 'update-quantity',
        VIEW => ''
    ];

    const BULK_IMPORT = [
        URI => 'bulk-import',
        VIEW => 'admin-views.magzineproduct.bulk-import'
    ];

    const UPDATED_PRODUCT_LIST = [
        URI => 'updated-product-list',
        VIEW => 'admin-views.magzineproduct.updated-product-list'
    ];

    const UPDATED_SHIPPING = [
        URI => 'updated-shipping',
        VIEW => ''
    ];

    const DENY = [
        URI => 'deny',
        VIEW => ''
    ];

    const APPROVE_STATUS = [
        URI => 'approve-status',
        VIEW => ''
    ];
    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.partials._search-product'

    ];
}
