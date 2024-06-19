<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * indexアクション (会社概要ページ)
     * 未ログインのユーザーは管理者側の会社概要ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_company_profile_page_on_the_admin(): void
    {
        $response = $this->get(route('admin.company.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * indexアクション (会社概要ページ)
     * ログイン済みの一般ユーザーは管理者側の会社概要ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_company_profile_page_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        $user->save();

        $response = $this->actingAs($user)->get(route('admin.company.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * indexアクション (会社概要ページ)
     * ログイン済みの管理者は管理者側の会社概要ページにアクセスできる
     */

    public function test_admins_can_access_the_company_profile_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $company = Company::factory()->create();

        $response = $this->get(route('admin.company.index'));
        $response->assertStatus(200);
    }

    /**
     * editアクション (会社概要編集ページ)
     * 未ログインのユーザーは管理者側の会社概要編集ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_company_profile_edit_page_on_the_admin(): void
    {
        $company = Company::factory()->create();
        $response = $this->get(route('admin.company.edit',[$company->id]));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * editアクション (会社概要編集ページ)
     * ログイン済みの一般ユーザーは管理者側の会社概要編集ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_company_profile_edit_page_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        $user->save();

        $response = $this->actingAs($user);
        $company = Company::factory()->create();

        $response = $this->get(route('admin.company.edit', [$company->id]));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * editアクション (会社概要編集ページ)
     * ログイン済みの管理者は管理者側の会社概要編集ページにアクセスできる
     */

    public function test_admins_can_access_the_company_profile_edit_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $company = Company::factory()->create();

        $response = $this->get(route('admin.company.edit', [$company->id]));
        $response->assertStatus(200);
    }

    /**
     * updateアクション (会社概要更新機能)
     * 未ログインのユーザーは管理者側の会社概要更新できない
     */
    public function test_non_login_users_cannot_access_the_company_profile_update_on_the_admin(): void
    {
        $company = Company::factory()->create();
        $response = $this->patch(route('admin.company.update', [$company->id]));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * updateアクション (会社概要更新機能)
     * ログイン済みの一般ユーザーは管理者側の会社概要更新できない
     */
    public function test_gereral_user_login_cannot_access_the_company_profile_update_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        $user->save();

        $response = $this->actingAs($user);
        $company = Company::factory()->create();
        $response = $this->patch(route('admin.company.update',[$company->id]));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * updateアクション (会社概要更新機能)
     * ログイン済みの管理者は管理者側の会社概要更新できる
     */

    public function test_admins_can_access_the_company_profile_update(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $company = Company::factory()->create();

        $response = $this->patch(route('admin.company.update',[$company->id]),[
            'name' => 'テスト2',
            'postal_code' => '1000000',
            'address' => 'テスト2',
            'representative' => 'テスト2',
            'establishment_date' => 'テスト2',
            'capital' => 'テスト2',
            'business' => 'テスト2',
            'number_of_employees' => 'テスト2',
        ]);
        $response->assertStatus(302);
    }
}
