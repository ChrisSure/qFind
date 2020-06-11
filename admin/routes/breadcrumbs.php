<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;


Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push('Home', route('admin.home'));
});

Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Users', route('admin.users.index'));
});

Breadcrumbs::for('admin.users.create', function ($trail) {
    $trail->parent('admin.users.index');
    $trail->push('Create', route('admin.users.create'));
});

Breadcrumbs::for('admin.users.show', function ($trail, $id) {
    $trail->parent('admin.users.index');
    $trail->push('Detail', route('admin.users.show', $id));
});

Breadcrumbs::for('admin.users.edit', function ($trail, $id) {
    $trail->parent('admin.users.show', $id);
    $trail->push('Edit', route('admin.users.edit', $id));
});
