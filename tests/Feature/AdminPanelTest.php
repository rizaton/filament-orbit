<?php

use App\Models\Item;
use App\Models\User;
use App\Models\Rental;
use App\Models\Category;

use Filament\Facades\Filament;
use function Pest\Livewire\livewire;
use App\Filament\Admin\Resources\ItemResource\Pages\ListItems;
use App\Filament\Admin\Resources\ItemResource\Pages\CreateItem;
use App\Filament\Admin\Resources\UserResource\Pages\ManageUsers;
use App\Filament\Admin\Resources\RentalResource\Pages\ListRentals;
use App\Filament\Admin\Resources\RentalResource\Pages\CreateRental;
use App\Filament\Admin\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Admin\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Admin\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Admin\Resources\RentalDetailResource\Pages\CreateRentalDetail;
use App\Filament\Admin\Resources\RentalDetailResource\Pages\EditRentalDetail;
use App\Filament\Admin\Resources\RentalDetailResource\Pages\ListRentalDetails;
use App\Filament\Admin\Resources\RentalResource\Pages\EditRental;
use App\Models\RentalDetail;

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
    $user = User::factory()->create();
    $this->actingAs($user);
});

it('bisa menampilkan category index page', function () {
    livewire(ListCategories::class)
        ->assertSuccessful();
});
it('bisa menampilkan category create form page', function () {
    livewire(CreateCategory::class)
        ->assertSuccessful();
});
it('bisa menampilkan category edit form page', function () {
    $category = Category::factory()->create();
    livewire(EditCategory::class, ['record' => $category->getKey()])
        ->assertSuccessful();
});

it('bisa menampilkan item index page', function () {
    livewire(ListItems::class)
        ->assertSuccessful();
});
it('bisa menampilkan item create form page', function () {
    livewire(CreateItem::class)
        ->assertSuccessful();
});
it('bisa menampilkan item edit form page', function () {
    $item = Item::factory()->create();
    livewire(EditCategory::class, ['record' => $item->getKey()])
        ->assertSuccessful();
});

it('bisa menampilkan rental index page', function () {
    livewire(ListRentals::class)
        ->assertSuccessful();
});
it('bisa menampilkan rental form page', function () {
    livewire(CreateRental::class)
        ->assertSuccessful();
});
it('bisa menampilkan rental edit form page', function () {
    $rental = Rental::factory()->create();
    livewire(EditRental::class, ['record' => $rental->getKey()])
        ->assertSuccessful();
});

it('bisa menampilkan rental detail index page', function () {
    livewire(ListRentalDetails::class)
        ->assertSuccessful();
});
it('bisa menampilkan rental detail form page', function () {
    livewire(CreateRentalDetail::class)
        ->assertSuccessful();
});
it('bisa menampilkan rental detail edit form page', function () {
    $rentalDetail = RentalDetail::factory()->create();
    livewire(EditRentalDetail::class, ['record' => $rentalDetail->getKey()])
        ->assertSuccessful();
});

it('bisa menampilkan user index page', function () {
    livewire(ManageUsers::class)
        ->assertSuccessful();
});
