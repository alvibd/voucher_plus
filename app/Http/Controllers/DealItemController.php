<?php

namespace App\Http\Controllers;

use App\Deal;
use App\DealItem;
use App\Tag;
use App\Vendor;
use Illuminate\Http\Request;

class DealItemController extends Controller
{

    public function store(Request $request, Vendor $vendor, Deal $deal)
    {
        if(!auth()->user()->hasRoleAndOwns('owner', $vendor))
        {
            abort(403);
        }elseif($deal->launching_date <= today())
        {
            abort(405, 'Not allowed to add item(s) to running deal.');
        }

        $this->validate($request, [
            'deal_items' => 'required|array|min:1',
            'deal_items.*.item_name' => 'required|string|min:5',
            'deal_items.*.item_description' => 'required|string|min:50',
            'deal_items.*.quantity' => 'required|integer|min:1',
            'deal_items.*.original_price' => 'required|numeric|min:200',
            'deal_items.*.offered_price' => 'required|numeric|lt:deal_items.*.original_price',
            'deal_items.*.tags' => 'required|array|min:1',
            'deal_items.*.tags.*' => 'required|string',
        ]);

        foreach ($request->deal_items as $item)
        {
            $deal_item = new DealItem();

            $deal_item->item_name = $item['item_name'];
            $deal_item->item_description = $item['item_description'];
            $deal_item->quantity = $item['quantity'];
            $deal_item->original_price = $item['original_price']*100;
            $deal_item->offered_price = $item['offered_price']*100;

            $deal_item->vendor()->associate($vendor);
            $deal_item->deal()->associate($deal);

            $deal_item->saveOrFail();

            $existing_tags = Tag::whereIn('name', $item['tags'])->get();

            $deal_item->tags()->sync($existing_tags->pluck('id')->all());

            // dd($existing_tags->pluck('id'));
            $new_tags = array_diff($item['tags'], $existing_tags->pluck('name')->all());

            foreach($new_tags as $tag)
            {
                $new_tag = new Tag();

                $new_tag->name = $tag;

                $new_tag->saveOrFail();

                $deal_item->tags()->attach($new_tag->id);
            }

        }

        return response()->json(['message' => 'Successfully created deal items.'], 200);
    }
}
