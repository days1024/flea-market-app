<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Address;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\LoginRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $user = auth()->user();
    $tab = $request->query('tab');
    $keyword = $request->query('keyword');

    $likedItems = collect();
    $notLikedItems = collect();

    if ($tab === 'mylist') {

        if ($user) {

            $likedItemIds = $user->likes()->pluck('item_id');

            $query = Item::whereIn('id', $likedItemIds);


            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $likedItems = $query->get();

        } else {
            $likedItems = collect();
        }

    }

    else {

        $query = Item::query();

        if ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        if ($user) {
            $likedItemIds = $user->likes()->pluck('item_id');

            $query->whereNotIn('id', $likedItemIds)
                  ->where('user_id', '!=', $user->id);
        }

        $notLikedItems = $query->get();
    }

    return view('index', compact(
        'tab',
        'keyword',
        'likedItems',
        'notLikedItems'
    ));
}


  public function item(ExhibitionRequest $request)
  {
      $data = $request->only([
        'name',
        'brand',
        'description',
        'price',
        'condition'
    ]);

    $data['user_id'] = Auth::id();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $data['image'] = $path;
    }


    $item = Item::create($data);

    $categories = $request->input('category', []);

    if ($request->has('category')) {
      $categoryIds = Category::whereIn('slug', $request->category)->pluck('id')->toArray();
    $item->categories()->attach($categoryIds);
    }
    return redirect('/');
  }


  public function show($item_id)
{
     $item = Item::with(['comments.user.profile','likes', 'categories'])->findOrFail($item_id); 
     $user = Auth::user();
     $profile = $user?->profile;
    return view('detail', compact('item','profile'));
}

public function comment(CommentRequest $request, $item_Id)
{
    Comment::create([
        'user_id' => Auth::id(),
        'item_id' => $item_Id,
        'content' => $request->content,
    ]);

    return back();
}


  public function mypage(Request $request)
  {
    $user = Auth::user();
    $page = $request->query('page', 'sell');
    $profile = $user->profile;


    if ($page === 'buy') {
        $items = Item::where('buyer_id', auth()->id())->get();
    } else if ($page === 'sell') {
        $items = Item::where('user_id', auth()->id())->get();
    }else {
        $items = Item::all();
    }

    return view('mypage',compact('items','profile'));
  }

    public function profile()
  {
    $user = Auth::user();

    $profile = Profile::where('user_id', $user->id)->first();
    return view('profile', compact('profile'));
  }

public function storeOrUpdate(ProfileRequest $request)
{
    $user = Auth::user();
    
   $data = $request->only([
   'user_name',
   'post_code',
   'address',
   'building'
    ]);
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $data['profile_image'] = $path;
    }

    $profile = Profile::where('user_id', $user->id)->first();
    
    if ($profile) {
        $profile->update($data);
    } else {
        Profile::create([
            'user_id' => $user->id,
            ...$data
        ]);
    }
    return redirect('/');
}


     public function purchase($item_id)
  {
    $item = Item::findOrFail($item_id);
    $user = Auth::user();

    $profile = Profile::where('user_id', $user->id)->first();
    $address = Address::where('user_id', $user->id)->first();
    return view('purchase', compact('item','profile','address'));
  }

      public function address($item_id)
  {
    $user = Auth::user();
    $profile = Profile::where('user_id', $user->id)->first();
    $address = Address::where('user_id', $user->id)->first();
    $item = Item::findOrFail($item_id);
    return view('address', compact('profile','item','address'));
  }


  public function sell()
  {
    $categories = Category::all();

    return view('sell', compact('categories'));
  }

  

  public function email()
  {
    return view('auth.email');
  }


  public function toggle($item_Id)
{
    $user = Auth::user();

    $like = Like::where('user_id', $user->id)
        ->where('item_id', $item_Id)
        ->first();

    if ($like) {
        // いいね解除
        $like->delete();
    } else {
        // いいね追加
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item_Id,
        ]);
    }

     return back();
}

public function updateAddress(AddressRequest $request, $item_id)
{
    $user = Auth::user();

    $data = $request->only([
   'post_code',
   'address',
   'building'
    ]);

    $address = Address::where('user_id', $user->id)->first();

     if ($address) {
        // 更新
        $address->update($data);
    } else {
        // 新規作成
        Address::create([
            'user_id' => $user->id,
            ...$data
        ]);
    }
    return redirect("/purchase/{$item_id}");
}

public function payment(PurchaseRequest $request, $item_id)
{
    $item = Item::findOrFail($item_id);
    if (!$request->address_id) {
    $profile = Profile::where('user_id', auth()->id())->first();

    $address = Address::create([
        'user_id' => auth()->id(),
        'post_code' => $profile->post_code,
        'address' => $profile->address,
    ]);

    $addressId = $address->id;  
        } else {
    $addressId = $request->address_id;
    }

    if ($item->buyer_id) {
        return redirect()->back()->with('error', 'この商品は購入済みです');
    }

    $item->buyer_id = auth()->id();
    $item->address_id = $addressId;
    $item->save();

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card', 'konbini'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'jpy',
                'product_data' => [
                    'name' => $item->name,
                ],
                'unit_amount' => $item->price,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => url('/success/'.$item->id),
        'cancel_url' => url('/purchase/'.$item->id),
    ]);

    return redirect($session->url);
}

public function success($item_id)
{
    $item = Item::findOrFail($item_id);

    $item->buyer_id = auth()->id();
    $item->save();

    return redirect('/');
}


public function logout()
{
    Auth::logout();
    return redirect('/login'); 
}

public function login(LoginRequest $request) {

    $credentials = $request->only('email','password');

    if (!Auth::attempt($credentials)) {
        throw ValidationException::withMessages([
            'email' => ['ログイン情報が登録されていません'],
        ]);
    }

    $request->session()->regenerate();

     if (!Auth::user()->hasVerifiedEmail()) {
        return redirect()->route('email.guide');
    }

    return redirect()->intended('/');

}
}
