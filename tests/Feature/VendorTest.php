<?php

namespace Tests\Feature;

use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaratrustSeeder;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(LaratrustSeeder::class);

        $this->user = factory(User::class)->create();

        $this->user->attachRole('user');
    }

    /**
     * @test
     */
    public function validate_required_fields_exists()
    {
        // dd($token);
        $response = $this->actingAs($this->user, 'api')->postJson('api/vendor/registration', [
            'organization_name' => null,
            'contact_no' => null,
            'address' => null,
            'postal_code' => null,
            'organization_type' => null
        ]);

        $response->assertJsonValidationErrors([
            'organization_name' => 'The organization name field is required.',
            'contact_no' => 'The contact no field is required.',
            'address' => 'The address field is required.',
            'postal_code' => 'The postal code field is required.',
            'organization_type' => 'The organization type field is required.'
        ]);
    }

    /**
     * @test
     */
    public function validate_organization_name_is_string()
    {
        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => 123,
            'contact_no' => 01654642132,
            'address' => 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...',
            'postal_code' => 1222,
            'organization_type' => 'corporation'
        ]);

        $response->assertJsonValidationErrors(['organization_name' => 'The organization name must be a string.']);
    }

    /**
     * @test
     */
    public function validate_organization_is_unique()
    {
        $vendor = factory(Vendor::class)->create();

        $vendor->user->attachRole('user');

        $response = $this->actingAs($vendor->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type
        ]);

        $response->assertJsonValidationErrors(['organization_name' => 'The organization name has already been taken.']);
    }

    /**
     * @test
     * todo later implementation
     */
    // public function validate_contact_no_is_number()
    // {
    //     $user = $this->authentication();

    //     $vendor = factory(Vendor::class)->make(['user_id' => $user->id]);

    //     $response = $this->actingAs($user, 'api')->postJson('/api/vendor/registration', [
    //         'organization_name' => $vendor->name,
    //         'contact_no' => 'not_number',
    //         'address' => $vendor->address,
    //         'postal_code' => $vendor->postal_code,
    //         'organization_type' => $vendor->organization_type
    //     ]);

    //     $response->assertJsonValidationErrors(['contact_no' => 'The contact no field must be string.']);
    // }

    /**
     * @test
     */
    public function validate_address_is_string()
    {

        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => 1586,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type
        ]);

        $response->assertJsonValidationErrors(['address' => 'The address must be a string.']);
    }

    /**
     * @test
     * todo later implementation
     */
    // public function validate_postal_code_is_numeric()
    // {
    //     $user = $this->authentication();

    //     $vendor = factory(Vendor::class)->make(['user_id' => $user->id]);

    //     $response = $this->actingAs($user, 'api')->postJson('/api/vendor/registration', [
    //         'organization_name' => $vendor->name,
    //         'contact_no' => $vendor->contact_no,
    //         'address' => $vendor->address,
    //         'postal_code' => 'not_numeric',
    //         'organization_type' => $vendor->organization_type
    //     ]);

    //     $response->assertJsonValidationErrors(['postal_code' => 'The postal code field must be string.']);
    // }

    /**
     * @test
     */
    public function validate_organization_type_is_valid()
    {

        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'organization_type' => 'not_valid_type'
        ]);

        $response->assertJsonValidationErrors(['organization_type' => 'The selected organization type is invalid.']);
    }

    /**
     * @test
     */
    public function vendor_registration_successful()
    {

        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type
        ]);

        $this->assertDatabaseCount('vendors', 2);
        $this->assertDatabaseHas('vendors', [
            // 'name' => json_encode(['en' => $vendor->name]),
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type,
            'user_id' => $this->user->id
        ]);

        $this->assertTrue($this->user->hasRole('owner'));

        $response->assertStatus(200)->assertExactJson(['message' => 'Successfully created your business.']);
    }
}