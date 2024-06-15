<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Admin;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * indexアクション
     * 未ログインのユーザーは管理者側のカテゴリ一覧ページにアクセスできない
     */
    public function test_unloging_users_cannot_list_of_categories_on_the_admin_side()
    {
        $response = $this->get('admin/categories');

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * indexアクション
     * ログイン済みの一般ユーザーは管理者側のカテゴリ一覧ページにアクセスできない
     */
    public function test_login_general_user_cannot_access_the_category_list_page_on_the_admin_side()
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

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertRedirect('admin/login');
    }

    /**
     * indexアクション
     * ログイン済みの管理者は管理者側のカテゴリ一覧ページにアクセスできる
     */
    public function test_login_admin_can_access_the_category_list_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get('admin/categories');
        $response->assertOk();
    }

    /**
     * storeアクション
     * 未ログインのユーザーは管理者側のカテゴリ一覧ページにアクセスできない
     */
    public function test_unlogin_users_cannot_register_categories_on_the_admin_side()
    {
        $response = $this->get('admin/categories');

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * storeアクション
     * ログイン済みの一般ユーザーは管理者側のカテゴリ更新ができない
     */
    public function test_login_general_user_cannot_register_category_on_the_admin_side()
    {
        $user = new User();
        // $user->name = "侍";
        // $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        // $user->postal_code = "123-4678";
        // $user->address = "東京都";
        // $user->phone_number = "080-0000-0000";

        $response = $this->actingAs($user)->get(route('admin.categories.index'));
        $response->assertRedirect('admin/login');
    }

    /**
     * storeアクション
     * ログイン済みの管理者は管理者側のカテゴリ更新ができる
     */
    public function test_login_admin_can_register_the_category_()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $category = Category::factory()->create();

        $response = $this->post(route('admin.categories.store'),[
            'name' => 'テスト1'
        ]);
        $response->assertRedirect(route('admin.categories.index'));
    }

    /**
     * updateアクション
     * 未ログインのユーザーは管理者側のカテゴリ更新できない
     */
    public function test_unlogin_users_cannot_update_categories_on_the_admin_side()
    {
        $response = $this->get('admin/categories');

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * updateアクション
     * ログイン済みの一般ユーザーは管理者側のカテゴリ更新できない
     */
    public function test_login_general_user_cannot_update_category_on_the_admin_side()
    {
        $user = new User();
        // $user->name = "侍";
        // $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        // $user->postal_code = "123-4678";
        // $user->address = "東京都";
        // $user->phone_number = "080-0000-0000";
        $category = Category::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.categories.update',[$category->id]));
        $response->assertRedirect('admin/login');
    }

    /**
     * updateアクション
     * ログイン済みの管理者は管理者側のカテゴリ更新ができる
     */
    public function test_login_admin_can_update_the_category_()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $category = Category::factory()->create();

        $response = $this->post(route('admin.categories.update',[$category->id]),[
            'name' => 'テスト10'
        ]);
        $response->assertRedirect(route('admin.categories.index'));
    }

    /**
     * destroyアクション
     * 未ログインのユーザーは管理者側のカテゴリ削除できない
     */
    public function test_unlogin_users_cannot_destroy_categories_on_the_admin_side()
    {
        $response = $this->get('admin/categories');

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * destroyアクション
     * ログイン済みの一般ユーザーは管理者側のカテゴリ削除できない
     */
    public function test_login_general_user_cannot_destroy_category_on_the_admin_side()
    {
        $user = new User();
        // $user->name = "侍";
        // $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        // $user->postal_code = "123-4678";
        // $user->address = "東京都";
        // $user->phone_number = "080-0000-0000";

        $category = Category::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.categories.destroy',[$category->id]));
        $response->assertRedirect('admin/login');
    }

    /**
     * destroyアクション
     * ログイン済みの管理者は管理者側のカテゴリ削除ができる
     */
    public function test_login_admin_can_destroy_the_category_()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $category = Category::factory()->create();

        $response = $this->post(route('admin.categories.destroy',[$category->id]));
        $response->assertRedirect(route('admin.categories.index'));
    }
}
