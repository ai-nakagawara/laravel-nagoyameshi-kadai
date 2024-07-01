<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    /**
     * index
     * 未ログインユーザー
     */
    public function test_not_login_user_access_company_page_index(): void
    {
        $response = $this->get(route('company.index'));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_company_page_index(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('company.index'));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_not_access_company_page_index(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('company.index'));

        $response->assertRedirect('admin/home');
    }
}
