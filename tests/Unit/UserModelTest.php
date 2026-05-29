<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\TwoFactorAuthentication;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'seller']);
    }

    /**
     * Test user has role method.
     */
    public function test_user_has_role(): void
    {
        $this->assertTrue($this->user->hasRole('seller'));
        $this->assertTrue($this->user->hasRole(['seller', 'broker']));
        $this->assertFalse($this->user->hasRole('admin'));
    }

    /**
     * Test user is active by default.
     */
    public function test_user_is_active_by_default(): void
    {
        $this->assertTrue($this->user->is_active);
    }

    /**
     * Test user can be deactivated.
     */
    public function test_user_can_be_deactivated(): void
    {
        $this->user->update(['is_active' => false]);

        $this->assertFalse($this->user->is_active);
    }

    /**
     * Test user has listed vehicles relationship.
     */
    public function test_user_has_listed_vehicles(): void
    {
        Vehicle::factory()->count(3)->create(['created_by' => $this->user->id]);

        $this->assertCount(3, $this->user->listedVehicles);
    }

    /**
     * Test user can enable 2FA.
     */
    public function test_user_can_enable_2fa(): void
    {
        $this->assertFalse($this->user->hasTwoFactorEnabled());

        $this->user->enableTwoFactor();

        $this->assertTrue($this->user->hasTwoFactorEnabled());
    }

    /**
     * Test user can disable 2FA.
     */
    public function test_user_can_disable_2fa(): void
    {
        $this->user->enableTwoFactor();
        $this->assertTrue($this->user->hasTwoFactorEnabled());

        $this->user->disableTwoFactor();

        $this->assertFalse($this->user->hasTwoFactorEnabled());
    }

    /**
     * Test user can create 2FA record.
     */
    public function test_user_can_get_or_create_2fa(): void
    {
        $twoFactor = $this->user->getOrCreateTwoFactorAuthentication();

        $this->assertInstanceOf(TwoFactorAuthentication::class, $twoFactor);
        $this->assertEquals($this->user->id, $twoFactor->user_id);
    }

    /**
     * Test broker license approval check.
     */
    public function test_broker_license_approval(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $broker = User::factory()->create(['role' => 'broker', 'dealer_license_status' => 'not_submitted']);

        $this->assertTrue($admin->isBrokerLicenseApproved());
        $this->assertFalse($broker->isBrokerLicenseApproved());

        $broker->update(['dealer_license_status' => 'approved']);
        $this->assertTrue($broker->isBrokerLicenseApproved());
    }
}
