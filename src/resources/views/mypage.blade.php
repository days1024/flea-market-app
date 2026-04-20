@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage-form__content">
    <div class="mypage-form__profile">
     @if(!empty($profile->profile_image))
         <div class="mypage-form__profile-image">
             <img src="{{ asset('storage/' . $profile->profile_image) }}">
         </div>
     @endif
     <div class="mypage-form__username"> 
         {{ $profile?->user_name ?? '' }}
     </div>
     <div class="mypage-form__profile-edit">
         <a href="/mypage/profile" class="form__button-profile">
             <button type="submit">プロフィールを編集</button>
         </a>
     </div>
    </div>
    <div class="sell-form__heading">
         <a href="/mypage?page=sell">出品した商品</a>
         <a href="/mypage?page=buy">購入した商品</a>
    </div>
    <div class="item-list">
      @foreach ($items as $item)
     <div class="item">
         <img src="{{ asset('storage/' . $item->image) }}">
         <p>{{ $item->name }}</p>
     </div>
     @endforeach
    </div>
    
</div>
@endsection