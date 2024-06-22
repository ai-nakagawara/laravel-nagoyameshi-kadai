<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * index
     * 未ログインのユーザーは会員側の会員情報トップページにアクセスできない
     */
    public function test_user_who_are_not_logged_in_cannot_access_the_member_page(): void
    {
        $response = $this->get(route('user.index'));

        $response->assertRedirect('login');
    }

    /**
     * index
     * ログイン済みの一般ユーザーは会員側の会員情報トップページにアクセスできる
     */
    public function test_logged_in_general_users_can_access_the_member_page()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('user.index'));
        $response->assertStatus(302);
    }

    /**
     * index
     * ログイン済みの管理者は会員側の会員情報ページにアクセスできない
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

        $response = $this->get(route('user.index'));
        $response->assertRedirect('login');
    }

    /**
     * edit
     * 未ログインのユーザーは会員側の会員情報編集ページにアクセスできない
     */
    public function test_user_who_are_not_logged_in_cannot_access_the_member_edit_page(): void
    {
        $response = $this->get(route('user.edit',1));

        $response->assertRedirect('login');
    }

    /**
     * edit
     * ログイン済みの一般ユーザーは会員側の他人の会員情報編集ページにアクセスできない
     */
    public function test_logged_in_general_users_can_access_the_member_edit_page_of_other_member()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('user.edit',2));
        $response->assertStatus(404);
    }

    /**
     * edit
     * ログイン済みの一般ユーザーは会員側の自身の会員情報編集ページにアクセスできる
     */
    public function test_logged_in_general_users_can_access_the_member_own_edit_page()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');

        $response = $this->actingAs($user)->get(route('user.edit',1));
        $response->assertStatus(302);
    }

    /**
     * edit
     * ログイン済みの管理者は会員側の会員情報編集ページにアクセスできない
     */
    public function test_logged_in_admin_cannot_access_the_member_edit_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('user.edit',1));
        $response->assertRedirect('login');
    }

    /**
     * update
     * 未ログインのユーザーは会員側の会員情報更新できない
     */
    public function test_user_who_are_not_logged_in_cannot_update_the_member_edit_page(): void
    {
        $response = $this->patch(route('user.update',1));

        $response->assertStatus(404);
    }

    /**
     * update
     * ログイン済みの一般ユーザーは会員側の他人の会員情報更新できない
     */
    public function test_logged_in_general_users_cannot_update_the_member_edit_page_of_other_member()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');

        $response = $this->actingAs($user)->patch(route('user.update',2));
        $response->assertStatus(404);
    }

    /**
     * update
     * ログイン済みの一般ユーザーは会員側の自身の会員情報更新できる
     */
    public function test_logged_in_general_users_can_update_the_member_own_page()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->patch(route('user.update',1));
        $response->assertStatus(302);
    }

    /**
     * update
     * ログイン済みの管理者は会員側の会員情報更新できない
     */
    public function test_logged_in_admin_cannot_update_the_member_edit_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->patch(route('user.update',1));
        $response->assertStatus(404);
    }
}
