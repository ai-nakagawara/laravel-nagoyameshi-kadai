<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use App\Models\Admin;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    /**
     * index
     * 未ログインユーザー
     */
    public function test_not_login_user_can_not_access_reservation_page_index(): void
    {
        $response = $this->get(route('reservations.index'));

        $response->assertRedirect('login');
    }

    /**
     * index
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_can_not_access_reservation_page_index(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('reservations.index'));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * index
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_reservation_page_index(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('reservations.index'));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_reservation_page_index(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('reservations.index'));

        $response->assertRedirect('login');
    }

    /**
     * create
     * 未ログイン
     */
    public function test_not_loging_user_not_access_reservation_page_create(): void
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reservations.create', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * create
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_reservation_page_create(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reservations.create', $restaurant));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * create
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_reservation_page_create(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reservations.create', $restaurant));

        $response->assertStatus(200);
    }

    /**
     * create
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_reservation_page_create(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reservations.create', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * store
     * 未ログイン
     */
    public function test_not_loging_user_not_access_reservation_page_store(): void
    {
        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reserved_datetime' => '2024-07-05 15:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

        $response = $this->post(route('restaurants.reservations.store', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * store
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_reservation_page_store(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reserved_datetime' => '2024-07-05 15:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];

        $response = $this->post(route('restaurants.reviews.store', $restaurant));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * store
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_reservation_page_store(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => '2024-07-05',
            'reservation_time' => '16:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];

        $response = $this->post(route('restaurants.reservations.store', $restaurant), $reservation);

        $response->assertRedirect(route('reservations.index', $restaurant->id));
    }

    /**
     * store
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_reservation_page_store(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'reserved_datetime' => '2024-07-05 15:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];
        $response = $this->post(route('restaurants.reservations.store', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * destroy
     * 未ログイン
     */
    public function test_not_loging_user_not_access_reservation_page_destroy(): void
    {
        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => '2024-07-05',
            'reservation_time' => '16:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

        $response = $this->delete(route('reservations.destroy', 507));

        $response->assertRedirect('login');
    }

    /**
     * destroy
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_reservations_page_destroy(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => '2024-07-05',
            'reservation_time' => '16:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->delete(route('reservations.destroy', 507));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * destroy
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_reservation_page_destroy(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => '2024-07-05',
            'reservation_time' => '16:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->delete(route('reservations.destroy', 509));

        $response->assertRedirect(route('reservations.index'));
    }

    /**
     * destroy
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_reservations_page_destroy(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $reservation = [
            'reservation_date' => '2024-07-05',
            'reservation_time' => '16:00',
            'number_of_people' => 20,
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];
        $response = $this->delete(route('reservations.destroy', 507));

        $response->assertRedirect('login');
    }
}
