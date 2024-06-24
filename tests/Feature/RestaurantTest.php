<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    /**
     * index
     * 未ログインのユーザーは会員側の店舗一覧ページにアクセスできる
     */
    public function test_user_who_are_not_logged_in_can_access_the_store_list_page(): void
    {
        $response = $this->get(route('restaurants.index'));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの一般ユーザーは会員側の店舗一覧ページにアクセスできる
     */
    public function test_logged_in_general_users_can_access_the_stpre_list_page()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('restaurants.index'));
        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの管理者は会員側の店舗一覧ページにアクセスできない
     */
    public function test_logged_in_admin_cannot_access_the_member_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('restaurants.index'));
        $response->assertRedirect(route('admin.home'));
    }

    /**
     * show
     * 未ログインのユーザーは会員側の店舗詳細ページにアクセスできる
     */
    public function test_user_who_are_not_logged_in_can_access_the_store_detail_page(): void
    {
        $response = $this->get(route('restaurants.show',1));

        $response->assertStatus(200);
    }

    /**
     * show
     * ログイン済みの一般ユーザーは会員側の店舗詳細ページにアクセスできる
     */
    public function test_logged_in_general_users_can_access_the_store_detail_page()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('restaurants.show',1));
        $response->assertStatus(200);
    }

    /**
     * show
     * ログイン済みの管理者は会員側の店舗詳細ページにアクセスできない
     */
    public function test_logged_in_admin_cannot_access_the_store_detail_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('restaurants.show',1));
        $response->assertRedirect(route('admin.home'));
    }
}
