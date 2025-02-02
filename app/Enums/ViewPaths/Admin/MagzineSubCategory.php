<?php

namespace App\Enums\ViewPaths\Admin;

enum MagzineSubCategory
{
    const LIST = [
        URI => 'view',
        VIEW => 'admin-views.magzinecategory.sub-category-view'
    ];
    const ADD = [
        URI => 'store',
        VIEW => ''
    ];
    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.magzinecategory.category-edit'
    ];
    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];
    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];
    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];
}
