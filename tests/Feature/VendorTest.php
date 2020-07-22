<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use App\Vendor;
use CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use LaratrustSeeder;
use Tests\TestCase;

class VendorTest extends TestCase
{
    use RefreshDatabase;
    // use WithoutMiddleware;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([LaratrustSeeder::class, CategorySeeder::class]);

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
            'organization_type' => null,
            'city' => null,
            'category' => null,
        ]);

        $response->assertJsonValidationErrors([
            'organization_name' => 'The organization name field is required.',
            'contact_no' => 'The contact no field is required.',
            'address' => 'The address field is required.',
            'postal_code' => 'The postal code field is required.',
            'organization_type' => 'The organization type field is required.',
            'city' => 'The city field is required.',
            'category' => 'The category field is required.'
        ]);
    }

    /**
     * @test
     */
    public function validate_organization_name_is_string()
    {
        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => 123,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'city' => $vendor->city,
            'organization_type' => $vendor->organization_type
        ]);

        $response->assertJsonValidationErrors(['organization_name' => 'The organization name must be a string.']);
    }

    /**
     * @test
     */
    public function validate_organization_is_unique()
    {
        $vendor = factory(Vendor::class)->create(['category_id' => Category::inRandomOrder()->first()->id]);

        $vendor->user->attachRole('user');

        $response = $this->actingAs($vendor->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type,
            'category' => $vendor->category_id,
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

        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => 1586,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type,
            'category' => $vendor->category_id,
        ]);

        $response->assertJsonValidationErrors(['address' => 'The address must be a string.']);
    }

    /**
     * @test
     */
    public function validate_city_name_is_string()
    {
        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'city' => 123,
            'organization_type' => $vendor->organization_type,
            'category' => $vendor->category_id,
        ]);

        $response->assertJsonValidationErrors(['city' => 'The city must be a string.']);
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

        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'organization_type' => 'not_valid_type',
            'category' => $vendor->category_id,
        ]);

        $response->assertJsonValidationErrors(['organization_type' => 'The selected organization type is invalid.']);
    }

    /**
     * @test
     */
    public function validate_tin_no_is_alphanumeric()
    {
        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'tin_no' => '213-asda',
            'organization_type' => $vendor->organization_type,
            'category' => $vendor->category_id,
        ]);

        $response->assertJsonValidationErrors(['tin_no' => 'The tin no may only contain letters and numbers.']);
    }

    public function validate_category()
    {
        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
            'organization_type' => $vendor->organization_type,
            'category' => 'not_integer',
        ]);

        $response->assertJsonValidationErrors(['category' => 'The category field must be an integer.']);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
            'organization_type' => $vendor->organization_type,
            'category' => Category::max('id')+1,
        ]);

        $response->assertJsonValidationErrors(['category' => 'The category does not exists.']);
    }

    /**
     * @test
     */
    public function vendor_registration_successful()
    {

        $vendor = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        // dd($vendor->category_id);

        $response = $this->actingAs($this->user, 'api')->postJson('/api/vendor/registration', [
            'organization_name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
            'organization_type' => $vendor->organization_type,
            'category' => $vendor->category_id,
        ]);

        $this->assertDatabaseCount('vendors', 1);
        $this->assertDatabaseHas('vendors', [
            // 'name' => $vendor->name,
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'organization_type' => $vendor->organization_type,
            'user_id' => $this->user->id
        ]);

        $this->assertTrue($this->user->hasRole('owner'));

        $response->assertStatus(200)->assertExactJson(['message' => 'Successfully created your business.']);
    }

    /**
     * @test
     */
    public function validate_only_owner_can_change_info()
    {
        $vendor = factory(Vendor::class)->create(['category_id' => Category::inRandomOrder()->first()->id]);

        $this->user->attachRole('owner');

        $this->assertDatabaseHas('vendors', [
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'city' => $vendor->city,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
        ]);

        $response = $this->actingAs($this->user, 'api')->patchJson("/api/vendor/edit/{$vendor->id}", [
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
        ]);

        // $response->dump();

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function validate_contact_no_for_edit()
    {
        $vendor = factory(Vendor::class)->create(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);
        $this->user->attachRole('owner');

        $response = $this->actingAs($this->user, 'api')->patchJson("/api/vendor/edit/{$vendor->id}", [
            'contact_no' => null,
            'address' => $vendor->address,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
        ]);

        // dd($response->getStatus());
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['contact_no' => 'The contact no field is required.']);
    }

    /**
     * @test
     */
    public function validate_address_for_edit()
    {
        $vendor = factory(Vendor::class)->create(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $this->user->attachRole('owner');

        $response = $this->actingAs($this->user, 'api')->patchJson("/api/vendor/edit/{$vendor->id}", [
            'contact_no' => $vendor->contact_no,
            'address' => null,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
        ]);

        $response->assertJsonValidationErrors(['address' => 'The address field is required.']);

        $response = $this->actingAs($this->user, 'api')->patchJson("/api/vendor/edit/{$vendor->id}", [
            'contact_no' => $vendor->contact_no,
            'address' => 234,
            'postal_code' => $vendor->postal_code,
            'tin_no' => $vendor->tin_no,
        ]);

        $response->assertJsonValidationErrors(['address' => 'The address must be a string.']);
    }

    /**
     * @test
     */
    public function validate_postal_code_for_edit()
    {
        $vendor = factory(Vendor::class)->create(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);
        $this->user->attachRole('owner');

        $response = $this->actingAs($this->user, 'api')->patchJson("/api/vendor/edit/{$vendor->id}", [
            'contact_no' => $vendor->contact_no,
            'address' => $vendor->address,
            'postal_code' => null,
            'tin_no' => $vendor->tin_no,
        ]);

        $response->assertJsonValidationErrors(['postal_code' => 'The postal code field is required.']);
    }



    /**
     * @test
     */
    public function edit_successful()
    {
        $vendor = factory(Vendor::class)->create(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);
        $this->user->attachRole('owner');

        $new_info = factory(Vendor::class)->make(['user_id' => $this->user->id, 'category_id' => Category::inRandomOrder()->first()->id]);

        $response = $this->actingAs($this->user, 'api')->patchJson("/api/vendor/edit/{$vendor->id}", [
            'contact_no' => $new_info->contact_no,
            'address' => $new_info->address,
            'postal_code' => $new_info->postal_code,
            'tin_no' => $new_info->tin_no,
        ]);

        $this->assertDatabaseHas('vendors', [
            'contact_no' => $new_info->contact_no,
            'address' => $new_info->address,
            'postal_code' => $new_info->postal_code,
            'tin_no' => $new_info->tin_no,
            ]);

        $response->assertStatus(200)->assertExactJson(['message' => 'Successfully saved your changes.']);
    }
}
