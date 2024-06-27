<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * createアクション
     * 未ログインのユーザーは有料プラン登録ページにアクセスできない
     */
    public function test_not_logged_in_user_can_not_access_premium_plan_page()
    {
        $response = $this->get(route('subscription.create'));

        $response->assertRedirect(route('login'));
    }

    /**
     * createアクション
     * ログイン済みの無料会員は有料プラン登録ページにアクセスできる
     */
    public function test_logged_in_free_user_can_access_premium_plan_page()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        // $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('subscription.create'));

        $response->assertStatus(200);
    }

    /**
     * createアクション
     * ログイン済みの有料会員は有料プラン登録ページにアクセスできる
     */
    public function test_logged_in_membership_user_can_not_access_premium_plan_page()
    {
        $user = new User();
        $user->email = "pon01ai02@gmail.com";
        $user->password = Hash::make('password');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $response = $this->get(route('subscription.create'));

        $response->assertRedirect(route('subscription.create'));
    }

    /**
     * createアクション
     */
    public function test_admin_user_cannot_access_premium_plan_page()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        // $admin->save();

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('subscription.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * storeアクション
     * 未ログインのユーザーは有料プラン登録できない
     */
    public function test_not_logged_in_user_can_not_premium_plan_store()
    {
        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->post(route('subscription.store'),$request_parameter);

        $response->assertRedirect(route('login'));
    }

    /**
     * storeアクション
     * ログイン済みの無料会員は有料プランに登録できる
     */
    public function test_logged_in_free_user_can_premium_plan_page_store()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->post(route('subscription.store'), $request_parameter);

        $response->assertRedirect(route('home'));
        $user->refresh();
        $this->assertTrue($user->subscribed('premium_plan'));

    }

    /**
     * storeアクション
     * ログイン済みの有料会員は有料プラン登録できない
     */
    public function test_logged_in_membership_user_can_not_premium_plan_page_store()
    {
        $user = new User();
        $user->email = "pon01ai02@gmail.com";
        $user->password = Hash::make('password');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->post(route('subscription.store'), $request_parameter);

        $response->assertRedirect(route('subscription.create'));
    }

    /**
     * storeアクション
     */
    public function test_admin_user_cannot_premium_plan_page_store()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->post(route('subscription.store'), $request_parameter);
        $response->assertRedirect(route('login'));
    }

    /**
     * editアクション
     * 未ログインのユーザーはお支払方法編集ページにアクセスできない
     */
    public function test_not_logged_in_user_can_not_premium_plan_edit()
    {
        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->get(route('subscription.edit'),$request_parameter);

        $response->assertRedirect(route('login'));
    }

    /**
     * editアクション
     * ログイン済みの無料会員はお支払方法編集ページにアクセスできない
     */
    public function test_logged_in_free_user_can_access_premium_plan_edit_page()
    {
        $user = new User();
        $user->name = "侍 三郎";
        $user->kana = "サムライ サブロウ";
        $user->email = "123test@example.com";
        $user->email_verified_at = "2024-06-13 09:57:38";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->get(route('subscription.edit'), $request_parameter);

        $response->assertStatus(302);

    }

    /**
     * editアクション
     * ログイン済みの有料会員はお支払編集ページにアクセスできる
     */
    public function test_logged_in_membership_user_can_access_premium_plan_page_edit()
    {
        $user = new User();
        $user->email = "pon01ai02@gmail.com";
        $user->password = Hash::make('password');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->get(route('subscription.edit'), $request_parameter);

        $response->assertStatus(200);
    }

    /**
     * editアクション
     */
    public function test_admin_user_cannot_premium_plan_page_edit()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->get(route('subscription.create'), $request_parameter);
        $response->assertRedirect(route('login'));
    }

    /**
     * updateアクション
     * 未ログインのユーザーはお支払方法の更新ができない
     */
    public function test_not_logged_in_user_can_not_update_premium_plan()
    {
        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->patch(route('subscription.update'),$request_parameter);

        $response->assertRedirect(route('login'));
    }

    /**
     * updateアクション
     * ログイン済みの無料会員はお支払方法の更新ができない
     */
    public function test_logged_in_free_user_can_not_update_premium_plan()
    {
        $user = new User();
        $user->name = "侍 三郎";
        $user->kana = "サムライ サブロウ";
        $user->email = "123test@example.com";
        $user->email_verified_at = "2024-06-13 09:57:38";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        // $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];

        $response = $this->patch(route('subscription.update'), $request_parameter);

        $response->assertStatus(302);

    }

    /**
     * updateアクション
     * ログイン済みの有料会員はお支払の更新ができる
     */
    public function test_logged_in_membership_user_can_update_premium_plan()
    {
        $user = new User();
        $user->email = "pon01ai02@gmail.com";
        $user->password = Hash::make('password');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $default_payment_method_id = $user->defaultPaymentMethod()->id;

        $request_parameter = [
            'paymentMethodId' => 'pm_card_mastercard'
        ];



        $response = $this->patch(route('subscription.update'), $request_parameter);
        $response->assertRedirect(route('home'));

        $this->assertNotEquals($default_payment_method_id, $user->defaultPaymentMethod()->id);
    }

    /**
     * updateアクション
     */
    public function test_admin_user_cannot_update_premium_plan()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $request_parameter = [
            'paymentMethodId' => 'pm_card_visa'
        ];
        $response = $this->patch(route('subscription.update'), $request_parameter);
        $response->assertRedirect(route('login'));
    }

    /**
     * cancel
     * 未ログインのユーザーは有料プランを解約できない
     */
    public function test_not_logged_in_user_can_not_premium_plan_cancel()
    {
        $response = $this->get(route('subscription.cancel'));

        $response->assertRedirect(route('login'));
    }

    /**
     * cancel
     * ログイン済みの無料会員は有料プランを解約できない
     */
    public function test_logged_in_free_user_can_not_premium_plan_cnacle()
    {
        $user = new User();
        $user->name = "侍 三郎";
        $user->kana = "サムライ サブロウ";
        $user->email = "123test@example.com";
        $user->email_verified_at = "2024-06-13 09:57:38";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        // $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('subscription.cancel'));

        $response->assertStatus(302);

    }

    /**
     * cancel
     * ログイン済みの有料会員はお支払の更新ができる
     */
    public function test_logged_in_membership_user_can_cancel_premium_plan()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('subscription.cancel'));
        $response->assertStatus(200);
    }

    /**
     * cancel
     */
    public function test_admin_user_cannot_cancel_premium_plan()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->get(route('subscription.cancel'));
        $response->assertRedirect(route('login'));
    }

    /**
     * destroy
     * 未ログインのユーザーは有料プランを解約できない
     */
    public function test_not_logged_in_user_can_not_premium_plan_destroy()
    {
        $response = $this->delete(route('subscription.destroy'));

        $response->assertRedirect(route('login'));
    }

    /**
     * destroy
     * ログイン済みの無料会員は有料プランを解約できない
     */
    public function test_logged_in_free_user_can_not_premium_plan_destroy()
    {
        $user = new User();
        $user->name = "侍 三郎";
        $user->kana = "サムライ サブロウ";
        $user->email = "123test@example.com";
        $user->email_verified_at = "2024-06-13 09:57:38";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4678";
        $user->address = "東京都";
        $user->phone_number = "080-0000-0000";
        // $user->save();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->delete(route('subscription.destroy'));

        $response->assertStatus(302);

    }

    /**
     * destroy
     * ログイン済みの有料会員は解約ができる
     */
    public function test_logged_in_membership_user_can_destroy_premium_plan()
    {
        $user = new User();
        $user->email = "test@example.com";
        $user->password = Hash::make('nagoyameshi');
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('subscription.destroy'));
        $response->assertRedirect(route('home'));
    }

    /**
     * destroy
     */
    public function test_admin_user_cannot_destroy_premium_plan()
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');

        $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);

        $response = $this->delete(route('subscription.destroy'));
        $response->assertRedirect(route('login'));
    }
}
