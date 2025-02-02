<?php

namespace App\Enums\ViewPaths\Vendor;

enum MagzineProduct
{
    const ADD = [
        URI => 'add',
        VIEW => 'vendor-views.magzineproduct.add-new'
    ];

 const SUBSCRIBE = [
        URI => 'subscribe',
        VIEW => ''
    ];


    const LIST = [
        URI => 'list',
        VIEW => 'vendor-views.magzineproduct.list'
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'vendor-views.magzineproduct.edit'
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'vendor-views.magzineproduct.view',
        ROUTE => 'vendor.magzineproducts.view'
    ];

    const SKU_COMBINATION = [
        URI => 'sku-combination',
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
        VIEW => 'vendor-views.magzineproduct.barcode'
    ];

    const BARCODE_GENERATE = [
        URI => 'barcode',
        VIEW => ''
    ];

    const EXPORT_EXCEL = [
        URI => 'export-excel',
        VIEW => ''
    ];

    const STOCK_LIMIT = [
        URI => 'stock-limit-list',
        VIEW => 'vendor-views.magzineproduct.stock-limit-list'
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
        VIEW => 'vendor-views.magzineproduct.partials._update_stock'
    ];

    const UPDATE_QUANTITY = [
        URI => 'update-quantity',
        VIEW => ''
    ];

    const BULK_IMPORT = [
        URI => 'bulk-import',
        VIEW => 'vendor-views.magzineproduct.bulk-import'
    ];
    const SEARCH = [
        URI => 'search',
        VIEW => 'vendor-views.partials._search-product'

    ];
}
