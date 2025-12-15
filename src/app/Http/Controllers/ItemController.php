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

        if ($tab === 'recommend') {
            $query = Item::query()->orderBy('created_at', 'desc');

            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            $items = $query->paginate(20);
        }

        if ($tab === 'mylist') {
            if (! auth()->check()) {
                return redirect('/login');
            }

            $items = auth()->user()->likedItems()
            ->where('items.user_id', '!=', auth()->id())
            ->orderBy('likes.created_at', 'desc')
            ->get();
        }

        return view('index', compact('items', 'tab'));
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
}
