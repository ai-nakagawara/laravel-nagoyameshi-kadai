<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * 会員一覧ページ
     * 未ログインのユーザーは管理者側の会員一覧ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_users_list_page_on_the_admin(): void
    {
        $response = $this->get(route('admin.users.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 会員一覧ページ
     * ログイン済みの一般ユーザーは管理者側の会員一覧ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_users_list_page_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $response = $this->actingAs($user)->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 会員一覧ページ
     * ログイン済みの管理者は管理者側の会員一覧ページにアクセスできる
     */

    public function test_admins_can_access_the_users_list_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
    }

    /**
     * 会員詳細ページ
     * 未ログインのユーザーは管理者側の会員詳細ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_users_detail_page_on_the_admin(): void
    {
        $response = $this->get(route('admin.users.show',1));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 会員詳細ページ
     * ログイン済みの一般ユーザーは管理者側の会員詳細ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_users_details_page_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $response = $this->actingAs($user)->get(route('admin.users.show',1));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * 会員詳細ページ
     * ログイン済みの管理者は管理者側の会員詳細ページにアクセスできる
     */

    public function test_admins_can_access_the_users_details_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $user = User::factory()->create();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('admin.users.show', [$user->id]));
        $response->assertOk();
    }
}
