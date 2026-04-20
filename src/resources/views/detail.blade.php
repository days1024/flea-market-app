@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail-form__content">
 <div class="detail-form__image">
     <img src="{{ asset('storage/' . $item->image) }}">
 </div>
 <div class="detail-form__item">
     <div class="item-name">{{ $item->name }}</div>
     <div class="item-brand">{{ $item->brand }}</div>
     <div class="item-price">
         <span class="item-price__symbol">¥</span>
         <span class="item-price__value">{{ $item->price }}</span>
         <span class="item-price__tax">(税込)</span>
     </div>
     <div class="item-count">
         <form class="item-like" action="/items/{{ $item->id }}/like" method="POST">
             @csrf
             <button type="submit" class="item-like-button" @if(!auth()->check()) disabled @endif>
             @if($item->likes->where('user_id', auth()->id())->count())
                 <img src="{{ asset('img/ハートロゴ_ピンク.png') }}">
              @else
                 <img src="{{ asset('img/ハートロゴ_デフォルト.png') }}">
              @endif
             </button>
             <div class="item-like-count">
                 {{ $item->likes->count() }}
             </div>
         </form>
         <div class="item-comment-logo">
             <img src="{{ asset('img/ふきだしロゴ.png') }}">
             <div class="item-comment-count">
                 {{ $item->comments->count() }}
             </div>
         </div>
     </div>
     <div class="form__button">
         <a href="/purchase/{{ $item->id }}">
             <button class="form__button-buy" type="submit">購入手続きへ</button>
         </a>
     </div>
     <div class="item-description">
         <span>商品説明</span>
         <div class="item-description__content">{{ $item->description}}</div>
     </div>
     <div class="item-information">
         <span class="item-information__title">商品の情報</span>
         <div class="item-catedory">
             <span class="item-catedory__title">カテゴリー</span>
             <span class="item-catedory__content">

                 @foreach($item->categories as $category)
                 <label class="category__label {{ $loop->first ? 'first-category' : '' }}">
                     {{ $category->category }}
                 </label>
                 @endforeach
             </span> 
         </div>
         <div class="item-condition">
             <span class="item-condition__title">商品の状態</span>
             @php
             $conditionLabels = [
             'excellent' => '良好',
             'good'      => '目立った傷や汚れなし',
             'fair'      => 'やや傷や汚れあり',
             'poor'      => '状態が悪い',
             ];
             @endphp
             <span class="item-condition__content">{{ $conditionLabels[$item->condition] ?? '未選択' }}</span>
         </div>
     </div>
     <div class="item-comment">
         <span class="item-comment__title">コメント({{ $item->comments->count() }})</span>
         @foreach($item->comments as $comment) 
         <div class="item-comment__image">
              <img src="{{ asset('storage/' . ($comment->user->profile->profile_image ?? 'default.png')) }}" >
             <div class="item-comment__user">
                 {{ $comment->user->profile->user_name ?? '' }}
             </div>
         </div>
         <div class="item-comment__content">
             <div class="item-comment__content-text">
                 {{ $comment->content }}
             </div>
         </div>

         @endforeach
         <form action="/items/{{ $item->id }}/comment"  method="POST">
            @csrf
             <div class="item-comment__group">
                 <div class="item-comment__group-title">
                     商品へのコメント
                 </div>
                 <div class="item-comment__group-text">
                     <textarea type="text" name="content"></textarea>
                 </div>
             </div>
             <div class="form__error">
                         @error('content')
                             {{ $message }}
                         @enderror
                 </div>
             <div class="form__item-comment">
                 <button class="form__button-comment" type="submit">コメントを送信する</button>
             </div>
         </form>  
     </div>
 </div>
</div>

@endsection