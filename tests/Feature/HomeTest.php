<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HomeTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * 未ログインのユーザーは会員側のトップページにアクセスできる
     */
    public function test_user_who_are_logged_in_can_access_the_member_top_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    /**
     * ログイン済みの一般ユーザーは会員側のトップページにアクセスできる
     */
    public function test_logged_in_general_users_can_access_the_member_top_page()
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        // $user->save();

        $response = $this->actingAs($user)->get(route('home'));
        $response->assertStatus(200);
    }

    /**
     * ログイン済みの管理者は会員側のトップページにアクセスできない
     */
    public function test_logged_in_admin_cannot_access_the_member_top_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        // $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('home'));
        $response->assertRedirect('admin/home');
    }
}
