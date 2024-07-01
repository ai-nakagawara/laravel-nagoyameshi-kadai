<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    /**
     * index
     * 未ログインユーザー
     */
    public function test_not_login_user_can_not_access_favorite_page_index(): void
    {
        $response = $this->get(route('favorites.index'));

        $response->assertRedirect('login');
    }

    /**
     * index
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_can_not_access_favorite_page_index(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('favorites.index'));

        $response->assertRedirect(route('subscription.create'));
    }

    /**
     * index
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_favorite_page_index(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('favorites.index'));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_favorite_page_index(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('favorites.index'));

        $response->assertRedirect('login');
    }

    /**
     * store
     * 未ログイン
     */
    public function test_not_loging_user_not_access_favorite_page_store(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->post(route('favorites.store', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * store
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_favorite_page_store(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->post(route('favorites.store', $restaurant));

        $response->assertRedirect(route('subscription.create'));
    }

    /**
     * store
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_favorite_page_store(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->post(route('favorites.store', $restaurant));

        $response->assertStatus(302);
    }

    /**
     * store
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_favorite_page_store(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->post(route('favorites.store', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * destroy
     * 未ログイン
     */
    public function test_not_loging_user_not_access_favorite_page_destroy(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('favorites.destroy', 507));

        $response->assertRedirect('login');
    }

    /**
     * destroy
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_favorite_page_destroy(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('favorites.destroy', 507));

        $response->assertRedirect(route('subscription.create'));
    }

    /**
     * destroy
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_favorite_page_destroy(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->delete(route('favorites.destroy', 643));

        $response->assertStatus(302);
    }

    /**
     * destroy
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_favorite_page_destroy(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('favorites.destroy', 507));

        $response->assertRedirect('login');
    }
}
