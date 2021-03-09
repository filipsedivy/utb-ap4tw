<?php declare(strict_types=1);

namespace App\Components\Menu;

interface MenuFactory
{
    public function create(): Menu;
}