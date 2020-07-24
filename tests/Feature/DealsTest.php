<?php

namespace Tests\Feature;

use App\Deal;
use App\User;
use App\Vendor;
use CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LaratrustSeeder;
use TagSeeder;
use Tests\TestCase;

class DealsTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public $vendor;

    public function setUp():void
    {
        parent::setUp();
        $this->seed([LaratrustSeeder::class, CategorySeeder::class]);

        $this->user = factory(User::class)->create();
        $this->user->attachRole('owner');

        $this->vendor = factory(Vendor::class)->create(['user_id' => $this->user->id]);
    }

    /**
     * @test
     */
    public function only_vendor_owner_can_create_deals()
    {
        $not_owner = factory(User::class)->create();

        $not_owner->attachRole('owner');

        $response = $this->actingAs($not_owner, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals");

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function validate_required_fields()
    {
        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => null,
            'campaign_description' => null,
            'terms_and_conditions' => null,
            'launching_date' => null,
            'expiration_date' => null,
            'final_redemption_date' => null,
        ]);

        $response->assertJsonValidationErrors([
            'campaign_name' => 'The campaign name field is required.',
            'campaign_description' => 'The campaign description field is required.',
            'terms_and_conditions' => 'The terms and conditions field is required.',
            'launching_date' => 'The launching date field is required.',
            'expiration_date' => 'The expiration date field is required.',
            'final_redemption_date' => 'The final redemption date field is required.',
        ]);
    }

    /**
     * @test
     */
    public function validate_campaign_name()
    {
        $deal = factory(Deal::class)->make();

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => 123,
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertJsonValidationErrors(['campaign_name' => 'The campaign name must be a string.']);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => 'short',
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertJsonValidationErrors(['campaign_name' => 'The campaign name must be at least 10 characters.']);
    }

    public function validate_campaign_description()
    {
        $deal = factory(Deal::class)->make();

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => 123,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertJsonValidationErrors(['campaign_description' => 'The campaign description must be a string.']);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => 'too short',
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertJsonValidationErrors(['campaign_description' => 'The campaign description must be at least 25 characters.']);
    }

    /**
     * @test
     */
    public function validate_terms_and_conditions()
    {
        $deal = factory(Deal::class)->make();

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => 123,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertJsonValidationErrors(['terms_and_conditions' => 'The terms and conditions must be a string.']);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => $deal->campaign_description ,
            'terms_and_conditions' => 'too short',
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertJsonValidationErrors(['terms_and_conditions' => 'The terms and conditions must be at least 25 characters.']);
    }

    /**
     * @test
     */
    public function validate_basic_dates()
    {
        $deal = factory(Deal::class)->make();

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => 'not_date',
            'expiration_date' => '23-132-3213',
            'final_redemption_date' => '23-132-3213',
        ]);

        $response->assertJsonValidationErrors([
            'launching_date' => 'The launching date is not a valid date.',
            'expiration_date' => 'The expiration date is not a valid date.',
            'final_redemption_date' => 'The final redemption date is not a valid date.'
        ]);
    }

    /**
     * @test
     */
    public function validate_dates_are_not_before_today()
    {
        $deal = factory(Deal::class)->make();

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
                'campaign_name' => $deal->campaign_name,
                'campaign_description' => $deal->campaign_description,
                'terms_and_conditions' => $deal->terms_and_conditions,
                'launching_date' => today()->subDay(),
                'expiration_date' => today()->subDay(),
                'final_redemption_date' => today()->subDay(),
            ]);

        $response->assertJsonValidationErrors([
                'launching_date' => 'The launching date must be a date after or equal to '.today()->addDay()->toDateString().'.',
                'expiration_date' => 'The expiration date must be a date after launching date.',
                'final_redemption_date' => 'The final redemption date must be a date after expiration date.'
            ]);
    }

    /**
     * @test
     */
    public function deal_creation_success()
    {
        $deal = factory(Deal::class)->make();

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $this->assertDatabaseHas('deals', [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertOk()->assertExactJson(['message' => 'Succesfully created your campaign']);
    }

    /**
     * @test
     */
    public function validate_new_deals_can_only_be_created_if_none_running()
    {
        $deal = factory(Deal::class)->create(['vendor_id' => $this->vendor->id]);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/create_deals", [
            'campaign_name' => $deal->campaign_name,
            'campaign_description' => $deal->campaign_description,
            'terms_and_conditions' => $deal->terms_and_conditions,
            'launching_date' => $deal->launching_date,
            'expiration_date' => $deal->expiration_date,
            'final_redemption_date' => $deal->final_redemption_date,
        ]);

        $response->assertStatus(405)->assertJson(['message' => 'Cannot create another deal before the current one expires.']);
    }
}
