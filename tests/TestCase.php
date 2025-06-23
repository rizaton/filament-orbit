<?php

namespace Tests;

use Filament\Facades\Filament;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(
            Filament::getPanel('admin'),
            Filament::getPanel('customer'),
        );
    }
}
