<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Like;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'recommend');
        $keyword = $request->get('keyword');

        if ($tab === 'recommend') {
            $query = Item::query()->orderBy('created_at', 'desc');

            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            if (!empty($keyword)) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }

            $items = $query->get();
        }

        if ($tab === 'mylist') {
            if (! auth()->check()) {
                return redirect('/login');
            }

            $query = auth()->user()->likedItems()
            ->where('items.user_id', '!=', auth()->id())
            ->orderBy('likes.created_at', 'desc');

            if (!empty($keyword)) {
                $query->where('items.name', 'LIKE', '%' . $keyword . '%');
            }

            $items = $query->get();
        }

        return view('index', compact('items', 'tab', 'keyword'));
    }

    public function search(Request $request)
    {
        $item = Item::where('name', 'LIKE',"%{$request->keyword}%")->first();

        $param = [
            'keyword' => $request->keyword,
            'item' => $item
        ];
        return view('index', $param);
    }

    public function show($item_id)
    {
        $item = Item::with([
            'condition',
            'categories',
            'comments.user',
            'likes'
        ])->findOrFail($item_id);

        $likesCount = $item->likes->count();

        $isLiked = auth()->check()
            ? $item->likes->contains('user_id', auth()->id()) : false;

        return view('show', compact('item', 'likesCount', 'isLiked'));
    }

    public function toggle($id)
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $item = Item::findOrFail($id);

        $like = Like::where('item_id', $id)->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'item_id' => $id,
                'user_id' => auth()->id(),
            ]);
        }

        return back();
    }

    public function commentStore(CommentRequest $request, $id)
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        Comment::create([
            'item_id' => $id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back();
    }

    public function sellIndex()
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $categories = Category::all();
        $conditions = Condition::all();

        return view('sell', compact('categories', 'conditions'));
    }

    public function sellStore(ExhibitionRequest $request)
    {
        $path = $request->file('item_image')->store('images', 'public');

        $item = Item::create([
            'user_id' => auth()->id(),
            'image' => $path,
            'condition_id' => $request->condition_id,
            'name' => $request->name,
            'brand_name' => $request->brand_name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        $item->categories()->sync($request->category_item);

        return redirect('/mypage');
    }
}
