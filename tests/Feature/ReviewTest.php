<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use App\Models\Admin;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    /**
     * index
     * 未ログイン
     */
    public function test_not_loging_user_not_access_review_page_index(): void
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.index', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * index
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_review_page_index(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.index', $restaurant));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_review_page_index(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.index', $restaurant));

        $response->assertStatus(200);
    }

    /**
     * index
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_review_page_index(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.index', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * create
     * 未ログイン
     */
    public function test_not_loging_user_not_access_review_page_create(): void
    {
        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.create', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * create
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_review_page_create(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.create', $restaurant));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * create
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_review_page_create(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.create', $restaurant));

        $response->assertStatus(200);
    }

    /**
     * create
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_review_page_create(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $response = $this->get(route('restaurants.reviews.create', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * store
     * 未ログイン
     */
    public function test_not_loging_user_not_access_review_page_store(): void
    {
        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

        $response = $this->post(route('restaurants.reviews.store', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * store
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_review_page_store(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '3',
            'content' => 'テスト',
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
    public function test_logged_in_premium_user_access_review_page_store(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->post(route('restaurants.reviews.store', $restaurant), $review);

        $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
    }

    /**
     * store
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_review_page_store(): void
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
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];
        $response = $this->post(route('restaurants.reviews.store', $restaurant));

        $response->assertRedirect('login');
    }

    /**
     * edit
     * 未ログイン
     */
    public function test_not_loging_user_not_access_review_page_edit(): void
    {
        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

        $response = $this->get(route('restaurants.reviews.edit', [$restaurant, 5]));

        $response->assertRedirect('login');
    }

    /**
     * edit
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_review_page_edit(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->get(route('restaurants.reviews.edit', [$restaurant, 5]));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * edit
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_review_page_edit(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->get(route('restaurants.reviews.edit', [$restaurant, 5]));

        $response->assertStatus(200);
    }

    /**
     * edit
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_review_page_edit(): void
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
            'score' => '3',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];
        $response = $this->get(route('restaurants.reviews.edit', [$restaurant, 5]));

        $response->assertRedirect('login');
    }

    /**
     * update
     * 未ログイン
     */
    public function test_not_loging_user_not_access_review_page_update(): void
    {
        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '5',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

        $response = $this->patch(route('restaurants.reviews.update', [$restaurant, 5]), $review);

        $response->assertRedirect('login');
    }

    /**
     * update
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_review_page_update(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '4',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->patch(route('restaurants.reviews.update', [$restaurant,5]), $review);

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * update
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_review_page_update(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '4',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->patch(route('restaurants.reviews.update', [$restaurant,5]), $review);

        $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
    }

    /**
     * update
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_review_page_update(): void
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
            'score' => '4',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];
        $response = $this->patch(route('restaurants.reviews.update', [$restaurant,5]), $review);

        $response->assertRedirect('login');
    }

    /**
     * destroy
     * 未ログイン
     */
    public function test_not_loging_user_not_access_review_page_destroy(): void
    {
        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '5',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];

        $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant, 10]));

        $response->assertRedirect('login');
    }

    /**
     * destroy
     * ログイン済みの無料会員
     */
    public function test_logged_in_free_user_access_review_page_destroy(): void
    {
        $user = new User();
        $user->email = "123test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '4',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant,10]));

        $response->assertRedirect(route('subscription.edit'));
    }

    /**
     * destroy
     * ログイン済みの有料会員
     */
    public function test_logged_in_premium_user_access_review_page_destroy(): void
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $restaurant = Restaurant::factory()->create();
        $review = [
            'score' => '4',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => Auth::user()->id,
        ];
        $response = $this->delete(route('restaurants.reviews.destroy', [$restaurant,10]));

        $response->assertRedirect(route('restaurants.reviews.index', $restaurant));
    }

    /**
     * destroy
     * ログイン済みの管理者
     */
    public function test_logged_in_admin_user_access_review_page_destroy(): void
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
            'score' => '4',
            'content' => 'テスト',
            'restaurant_id' => $restaurant->id,
            'user_id' => 110,
        ];
        $response = $this->delete(route('restaurants.reviews.update', [$restaurant,10]));

        $response->assertRedirect('login');
    }
}
