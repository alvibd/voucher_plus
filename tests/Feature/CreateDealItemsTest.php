<?php

namespace Tests\Feature;

use App\Deal;
use App\DealItem;
use App\Tag;
use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateDealItemsTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    private $vendor;

    private $deal;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = factory(User::class)->create();

        $this->user->attachRole('owner');

        $this->vendor = factory(Vendor::class)->create(['user_id' => $this->user->id]);

        $this->deal = factory(Deal::class)->create(['vendor_id' => $this->vendor->id]);
    }

    /**
     * @test
     */
    public function only_vendor_owner_can_create_deal_items()
    {
        $not_owner = factory(User::class)->create();

        $not_owner->attachRole('owner');

        $response = $this->actingAs($not_owner, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items");

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function validate_lock_out_for_running_deals()
    {
        $running_deal = factory(Deal::class)->create(['vendor_id' => $this->vendor->id, 'launching_date' => today()]);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$running_deal->id}/create_items");

        $response->assertStatus(405)->assertJson(['message' => 'Not allowed to add item(s) to running deal.']);
    }

    /**
     * @test
     */
    public function validate_required_fields()
    {
        // 'item_name' => $deal_item->item_name,
        // 'item_description' => $deal_item->item_name,
        // 'quantity' => $deal_item->item_name,
        // 'original_price' => $deal_item->item_name,
        // 'offered_price' => $deal_item->item_name,

        $deal_items = factory(DealItem::class, 3)->make([
            'item_name' => null,
            'item_description' => null,
            'quantity' => null,
            'original_price' => null,
            'offered_price' => null,
        ]);

        $request = [];

        foreach ($deal_items as $key => $item)
        {
            // $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

            $request['deal_items'][$key] = [
                'item_name' => $item->item_name,
                'item_description' => $item->item_description,
                'quantity' => $item->quantity,
                'original_price' => $item->original_price,
                'offered_price' => $item->offered_price,
                'tags' => [],
            ];
        }

        // dd($request);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", ['deal_items' => null]);

        // $response->dump();

        $response->assertJsonValidationErrors(['deal_items' => 'The deal items field is required.']);

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors([
            "deal_items.0.item_name" => "The deal_items.0.item_name field is required.",
            "deal_items.1.item_name" => "The deal_items.1.item_name field is required.",
            "deal_items.2.item_name" => "The deal_items.2.item_name field is required.",
            "deal_items.0.quantity" => "The deal_items.0.quantity field is required.",
            "deal_items.1.quantity" => "The deal_items.1.quantity field is required.",
            "deal_items.2.quantity" => "The deal_items.2.quantity field is required.",
            "deal_items.0.original_price" => "The deal_items.0.original_price field is required.",
            "deal_items.1.original_price" => "The deal_items.1.original_price field is required.",
            "deal_items.2.original_price" => "The deal_items.2.original_price field is required.",
            "deal_items.0.offered_price" => "The deal_items.0.offered_price field is required.",
            "deal_items.1.offered_price" => "The deal_items.1.offered_price field is required.",
            "deal_items.2.offered_price" => "The deal_items.2.offered_price field is required.",
            "deal_items.0.tags" => "The deal_items.0.tags field is required.",
            "deal_items.1.tags" => "The deal_items.1.tags field is required.",
            "deal_items.2.tags" => "The deal_items.2.tags field is required.",
        ]);

    }

    /**
     * @test
     */
    public function validate_item_name_is_string()
    {
        $deal_item = factory(DealItem::class)->make(['item_name' => 12345]);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.item_name" => "The deal_items.0.item_name must be a string."]);
    }


    /**
     * @test
     */
    public function validate_item_name_is_not_too_short()
    {
        $deal_item = factory(DealItem::class)->make(['item_name' => 'not']);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.item_name" => "The deal_items.0.item_name must be at least 5 characters."]);
    }

    /**
     * @test
     */
    public function validate_item_description_is_string()
    {
        $deal_item = factory(DealItem::class)->make(['item_description' => 12345]);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.item_description" => "The deal_items.0.item_description must be a string."]);
    }

    /**
     * @test
     */
    public function validate_item_description_is_not_too_short()
    {
        $deal_item = factory(DealItem::class)->make(['item_description' => 'too short']);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.item_description" => "The deal_items.0.item_description must be at least 50 characters."]);
    }

    /**
     * @test
     */
    public function validate_quantity_is_more_than_zero()
    {
        $deal_item = factory(DealItem::class)->make(['quantity' => 0]);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.quantity" => "The deal_items.0.quantity must be at least 1."]);
    }

    /**
     * @test
     */
    public function validate_quantity_is_integer()
    {
        $deal_item = factory(DealItem::class)->make(['quantity' => 9.8]);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.quantity" => "The deal_items.0.quantity must be an integer."]);
    }

    /**
     * @test
     */
    public function validate_quantity_is_not_string()
    {
        $deal_item = factory(DealItem::class)->make(['quantity' => 'abc']);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.quantity" => "The deal_items.0.quantity must be an integer."]);
    }

    /**
     * @test
     */
    public function validate_original_price_is_more_than_minimum()
    {
        $deal_item = factory(DealItem::class)->make(['original_price' => 195]);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.original_price" => "The deal_items.0.original_price must be at least 200."]);
    }

    /**
     * @test
     */
    public function validate_original_price_is_numeric()
    {
        $deal_item = factory(DealItem::class)->make(['original_price' => 'asvc']);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.original_price" => "The deal_items.0.original_price must be a number."]);
    }

    /**
     * @test
     */
    public function validate_offered_price_is_numeric()
    {
        $deal_item = factory(DealItem::class)->make(['offered_price' => 'asvc']);

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.offered_price" => "The deal_items.0.offered_price must be a number."]);
    }

    /**
     * @test
     */
    public function validate_offered_price_is_less_than_original_price()
    {
        $deal_item = factory(DealItem::class)->make();

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->original_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.offered_price" => "The deal_items.0.offered_price must be less than {$deal_item->original_price}."]);
    }

    /**
     * @test
     */
    public function validate_tags_is_an_array()
    {
        $deal_item = factory(DealItem::class)->make();

        // dd($deal_item);

        $request = [];

        // $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->original_price,
            'tags' => 'ads',
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.tags" => "The deal_items.0.tags must be an array."]);
    }

    /**
     * @test
     */
    public function validate_tags_are_strings()
    {
        $deal_item = factory(DealItem::class)->make();

        // dd($deal_item);

        $request = [];

        // $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->original_price,
            'tags' => [123],
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertJsonValidationErrors(["deal_items.0.tags.0" => "The deal_items.0.tags.0 must be a string."]);
    }

    /**
     * @test
     */
    public function deal_items_creation_successfull()
    {
        $deal_item = factory(DealItem::class)->make();

        // dd($deal_item);

        $request = [];

        $tags = Tag::inRandomOrder()->take(rand(1,10))->pluck('name');

        $request['deal_items'][0] = [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price,
            'offered_price' => $deal_item->offered_price,
            'tags' => $tags,
        ];

        $response = $this->actingAs($this->user, 'api')->postJson("/api/vendor/{$this->vendor->id}/{$this->deal->id}/create_items", $request);

        // $response->dump();

        $response->assertOk()->assertExactJson(['message' => 'Successfully created deal items.']);

        $this->assertDatabaseHas('deal_items', [
            'item_name' => $deal_item->item_name,
            'item_description' => $deal_item->item_description,
            'quantity' => $deal_item->quantity,
            'original_price' => $deal_item->original_price*100,
            'offered_price' => $deal_item->offered_price*100,
        ]);

        $this->assertDatabaseCount('deal_item_tag', $tags->count());
    }
}
