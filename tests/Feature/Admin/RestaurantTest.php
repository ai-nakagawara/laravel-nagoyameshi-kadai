<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\User;
use App\Models\Restaurant;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;
    /**
     * indexアクション (店舗一覧ページ)
     * 未ログインのユーザーは管理者側の店舗一覧ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_restaurants_list_page_on_the_admin(): void
    {
        $response = $this->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * indexアクション (店舗一覧ページ)
     * ログイン済みの一般ユーザーは管理者側の店舗一覧ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_restaurants_list_page_on_the_admin(): void
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

        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * indexアクション (店舗一覧ページ)
     * ログイン済みの管理者は管理者側の店舗一覧ページにアクセスできる
     */

    public function test_admins_can_access_the_restaurant_list_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('admin.restaurants.index'));
        $response->assertStatus(200);
    }


    /**
     * showアクション(店舗詳細ページ)
     * 未ログインのユーザーは管理者側の店舗詳細ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_restaurant_detail_page_on_the_admin(): void
    {
        $response = $this->get(route('admin.restaurants.show',1));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * showアクション(店舗詳細ページ)
     * ログイン済みの一般ユーザーは管理者側の店舗詳細ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_restaurant_details_page_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $response = $this->actingAs($user)->get(route('admin.restaurants.show',1));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * showアクション(店舗詳細ページ)
     * ログイン済みの管理者は管理者側の店舗詳細ページにアクセスできる
     */

     public function test_admins_can_access_the_restaurant_details_page(): void
     {

        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.show',[$restaurant]));
        $response->assertOk();
     }


     /**
     * createアクション(店舗登録ページ)
     * 未ログインのユーザーは管理者側の店舗登録ページにアクセスできない
     */
    public function test_non_login_users_cannot_access_the_restaurant_registration_page_on_the_admin(): void
    {
        $response = $this->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * createアクション(店舗登録ページ)
     * ログイン済みの一般ユーザーは管理者側の店舗登録ページにアクセスできない
     */
    public function test_gereral_user_login_cannot_access_the_restaurant_registration_page_on_the_admin(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * createアクション(店舗登録ページ)
     * ログイン済みの管理者は管理者側の店舗登録ページにアクセスできる
     */

     public function test_admins_can_access_the_restaurant_registration_page(): void
     {

        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('admin.restaurants.create'));
        $response->assertOk();
    }


     /**
     * storeアクション(店舗登録機能)
     * 未ログインのユーザーは管理者側の店舗登録できない
     */
    public function test_non_login_users_can_not_register_restaurants(): void
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->post(route('admin.restaurants.store',[$restaurant->id]));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * storeアクション(店舗登録機能)
     * ログイン済みの一般ユーザーは管理者側の店舗登録できない
     */
    public function test_gereral_user_can_not_register_restaurants(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.restaurants.store',[$restaurant->id]));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * storeアクション(店舗登録機能)
     * ログイン済みの管理者は管理者側の店舗登録できる
     */

     public function test_admins_can_register_restaurants(): void
     {

        $admin = new Admin();
        $admin->email = 'admin@example.com';


        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->post(route('admin.restaurants.create'),[
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => 1000000,
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'seating_capacity' => 50
        ])->post(route('admin.restaurants.store'));
        $response->assertRedirect(route('admin.restaurants.index'));
     }

     /**
     * editアクション(店舗編集ページ)
     * 未ログインのユーザーは管理者側の店舗編集ページをみることができない
     */
    public function test_non_login_users_cannot_access_the_restaurant_edit_page(): void
    {

        $response = $this->get(route('admin.restaurants.edit',1));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * editアクション(店舗編集ページ)
     * ログイン済みの一般ユーザーは管理者側の店舗編集ページを見ることができない
     */
    public function test_gereral_user_login_cannot_access_the_restaurant_edit_page(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $response = $this->actingAs($user)->get(route('admin.restaurants.edit',1));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * editアクション(店舗編集ページ)
     * ログイン済みの管理者は管理者側の店舗編集ページを見ることができる
     */

     public function test_admin_user_login_can_access_the_restaurant_edit_page(): void
     {
        $admin = new Admin();
        $admin->email = 'admin@example.com';

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();

        $response = $this->post(route('admin.restaurants.edit',[$restaurant->id]));
        $response->assertOk();
        // $response->assertRedirect(route('admin.restaurants.index'));
     }

     /**
     * updateアクション(店舗更新機能)
     * 未ログインのユーザーは管理者側の店舗更新ができない
     */
    public function test_non_login_users_can_not_update_restaurants(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.update', [$restaurant->id]));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * updateアクション(店舗更新機能)
     * ログイン済みの一般ユーザーは管理者側の店舗更新できない
     */
    public function test_gereral_user_can_not_update_restaurants(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.update', [$restaurant->id]));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * updateアクション(店舗更新機能)
     * ログイン済みの管理者は管理者側の店舗登録できる
     */

     public function test_admins_can_update_restaurants(): void
     {

        $admin = new Admin();
        $admin->email = 'admin@example.com';


        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->post(route('admin.restaurants.update', [$restaurant->id]),[
            'seating_capacity' => 60
        ]);
        // $response->assertRedirect(route('admin.restaurants.index'));
        $response->assertRedirect();
     }

      /**
     * destroyアクション(店舗削除機能)
     * 未ログインのユーザーは管理者側の店舗削除ができない
     */
    public function test_non_login_users_can_not_delete_restaurants(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.destroy', [$restaurant->id]));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * destrpuアクション(店舗削除機能)
     * ログイン済みの一般ユーザーは管理者側の店舗削除できない
     */
    public function test_gereral_user_can_not_delete_restaurants(): void
    {
        $user = new User();
        $user->name = "侍";
        $user->kana = "サムライ";
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";

        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.destroy', [$restaurant->id]));
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * destroyアクション(店舗削除機能)
     * ログイン済みの管理者は管理者側の店舗登録できる
     */

     public function test_admins_can_destroy_restaurants(): void
     {

        $admin = new Admin();
        $admin->email = 'admin@example.com';


        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->post(route('admin.restaurants.destroy', [$restaurant->id]));
        $response->assertRedirect(route('admin.restaurants.index'));
     }
}
