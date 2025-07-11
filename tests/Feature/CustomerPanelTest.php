<?php

use function Pest\Livewire\livewire;
use App\Models\User;
use App\Models\Rental;
use Filament\Facades\Filament;

use App\Filament\Customer\Resources\ItemResource\Pages\ManageItems;
use App\Filament\Customer\Resources\RentalResource\Pages\EditRental;
use App\Filament\Customer\Resources\RentalResource\Pages\ListRentals;
use App\Filament\Customer\Resources\RentalResource\Pages\CreateRental;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('customer'),
    );
    $user = User::factory()->create();
    $this->actingAs($user);
});

it('bisa menampilkan item index page', function () {
    livewire(ManageItems::class)
        ->assertSuccessful();
});

it('bisa menampilkan rental list page', function () {
    livewire(ListRentals::class)
        ->assertSuccessful();
});

it('bisa menampilkan create rental form', function () {
    livewire(CreateRental::class)
        ->assertSuccessful();
});

it('bisa menampilkan edit rental date form', function () {
    $rental = Rental::factory()->create();

    livewire(EditRental::class, ['record' => $rental->getKey()])
        ->assertSuccessful();
});
