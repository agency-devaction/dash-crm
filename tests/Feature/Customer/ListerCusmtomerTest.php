<?php

use App\Livewire\Customers;
use App\Models\{Customer, User};
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{actingAs, get};

it('should be able to access the route admin/customer', function () {
    actingAs(User::factory()->create());

    get(route('admin.customer'))
        ->assertOk();
});

test("let's create a livewire component to list all customer in the page", function () {
    actingAs(User::factory()->admin()->create());

    $customers = Customer::factory()->count(10)->create();

    $lw = Livewire::test(Customers\Index::class);
    $lw->assertSet('customers', function ($customers) {
        expect($customers)
            ->toBeInstanceOf(LengthAwarePaginator::class)
            ->toHaveCount(10);

        return true;
    });

    foreach ($customers as $customer) {
        $lw->assertSee($customer->name);
    }
});

test('check the table format', function () {
    actingAs(User::factory()->admin()->create());

    Livewire::test(Customers\Index::class)
        ->assertSet(
            'headers',
            [
                ['key' => 'id', 'label' => '#'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'],
            ]
        );
});

test('should be able to filter by name and email', function () {
    actingAs(User::factory()->admin()->create());
    Customer::factory()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);
    Customer::factory()->create(['name' => 'Mario', 'email' => 'mario_guy@gmail.com']);

    Livewire::test(Customers\Index::class)
        ->assertSet('customers', function ($customer) {
            expect($customer)
                ->toHaveCount(2);

            return true;
        })
        ->set('search', 'Mario')
        ->assertSet('customers', function ($customer) {
            expect($customer)
                ->toHaveCount(1)
                ->first()
                ->name
                ->toBe('Mario');

            return true;
        })
        ->set('search', 'mario_guy')
        ->assertSet('customers', function ($customer) {
            expect($customer)
                ->toHaveCount(1)
                ->first()
                ->name
                ->toBe('Mario');

            return true;
        });
});

test('should be able to list deleted customer', function () {
    $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);

    User::factory()->count(2)->create(['deleted_at' => now()]);

    actingAs($admin);
    Livewire::test(Customers\Index::class)
        ->assertSet('customer', function ($customer) {
            expect($customer)
                ->toHaveCount(1);

            return true;
        })
        ->set('search_trashed', true)
        ->assertSet('customer', function ($customer) {
            expect($customer)
                ->toHaveCount(2);

            return true;
        });
})->todo();

test('should be able to sort by name', function () {
    actingAs(User::factory()->admin()->create());
    Customer::factory()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);
    Customer::factory()->create(['name' => 'Mario', 'email' => 'mario@gmail.com']);

    Livewire::test(Customers\Index::class)
        ->set('sortBy', ['column' => 'name', 'direction' => 'asc'])
        ->assertSet('customers', function ($customer) {
            expect($customer)
                ->first()->name->toBe('Joe Doe')
                ->and($customer)->last()->name->toBe('Mario');

            return true;
        })
        ->set('sortBy', ['column' => 'name', 'direction' => 'desc'])
        ->assertSet('customers', function ($customer) {
            expect($customer)
                ->first()->name->toBe('Mario')
                ->and($customer)->last()->name->toBe('Joe Doe');

            return true;
        });
});

it('should be able to paginate the result', function () {
    actingAs(User::factory()->admin()->create());
    Customer::factory()->count(100)->create();

    Livewire::test(Customers\Index::class)
        ->set('sortByColumn', 'name')
        ->assertSet('customers', function (LengthAwarePaginator $customer) {
            expect($customer)
                ->toHaveCount(15);

            return true;
        })
        ->set('perPage', 20)
        ->assertSet('customers', function (LengthAwarePaginator $customer) {
            expect($customer)
                ->toHaveCount(20);

            return true;
        });
});
