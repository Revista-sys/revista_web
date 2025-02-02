<?php

namespace App\Enums\ViewPaths\Admin;

enum Prime
{
    const LIST = [
        URI => 'list',
        VIEW => 'admin-views.prime.view'
    ];

    const ADD = [
        URI => 'add',
        VIEW => ''
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const UPDATE = [
        URI => 'update',
        VIEW => 'admin-views.prime.edit',
        ROUTE => 'admin.prime.list'
    ];
}
