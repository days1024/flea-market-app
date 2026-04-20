@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
 <div class="sell-form__content">
     <div class="sell-form__heading">
         <h1>商品の出品</h1>
     </div>
     <form class="form" action="/sell" method="POST" enctype="multipart/form-data">
     @csrf
         <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">商品画像</span>
             </div>
             <div class="form__group-content">
                 <div class="form__group-image">
                     <label for="image" class="form__button-image">画像を選択する
                     </label>
                     <input type="file" name="image" id="image" style="display:none;">
                 </div>  
             </div>
             <div class="form__error">
                 @error('image')
                     {{ $message }}
                 @enderror
             </div>
         </div>
         <div class="sell-form__sub-heading">
             <h2>商品の詳細</h2>
         </div>
         <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">カテゴリー</span>
             </div>
         <div class="category">
             @php
              $categories = [
             'fashion' => 'ファッション',
             'home' => '家電',
             'interior' => 'インテリア',
             'ladies' => 'レディース',
             'mens' => 'メンズ',
             'cosmetics' => 'コスメ',
             'book' => '本',
             'game' => 'ゲーム',
             'sports' => 'スポーツ',
             'kitchen' => 'キッチン',
             'handmade' => 'ハンドメイド',
             'accessories' => 'アクセサリー',
             'toys' => 'おもちゃ',
             'kids' => 'ベビー・キッズ',
             ];
              @endphp

             @foreach($categories as $slug => $label)
             <input type="checkbox" 
               name="category[]" 
               value="{{ $slug }}" 
               id="{{ $slug }}" 
               hidden
               {{ in_array($slug, old('category', [])) ? 'checked' : '' }}>
             <label for="{{ $slug }}" class="category__label">{{ $label }}</label>
             @endforeach
         </div>
             <div class="form__error">
                 @error('category')
                     {{ $message }}
                 @enderror
             </div>
         </div>
         <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">商品の状態</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <select  class="contect-form__item-select" name="condition">
                     <option value="" disabled {{ old('condition') ? '' : 'selected' }}>選択してください</option>
                     <option value="excellent" {{ old('condition') === 'excellent' ? 'selected' : '' }}>
                         良好
                     </option>
                     <option value="good" {{ old('condition') === 'good' ? 'selected' : '' }}>
                         目立った傷や汚れなし
                     </option>
                     <option value="fair" {{ old('condition') === 'fair' ? 'selected' : '' }}>
                          やや傷や汚れあり
                     </option>
                     <option value="poor" {{ old('condition') === 'poor' ? 'selected' : '' }}>
                         状態が悪い
                     </option>
                     </select>
                 </div>
                 <div class="form__error">
                     @error('condition')
                         {{ $message }}
                     @enderror
                 </div>
             </div>
         </div>
         <div class="sell-form__sub-heading">
             <h2>商品名と説明</h2>
         </div>
         <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">商品名</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="name" value="{{ old('name') }}">
                 </div>
                 <div class="form__error">
                     @error('name')
                         {{ $message }}
                     @enderror
                 </div>
             </div>
         </div>
          <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">ブランド名</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="brand" value="{{ old('brand') }}"/>
                 </div>
                 <div class="form__error">
                     @error('brand')
                         {{ $message }}
                     @enderror
                 </div>
             </div>
         </div>
          <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">商品の説明</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <textarea type="text" name="description">{{ old('description') }}</textarea>
                 </div>
                 <div class="form__error">
                     @error('description')
                         {{ $message }}
                     @enderror
                 </div>
             </div>
         </div>
          <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">販売価格</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="price" value="{{ old('price') }}" />
                 </div>
                 <div class="form__error">
                     @error('price')
                         {{ $message }}
                     @enderror
                 </div>
             </div>
         </div>
         <div class="form__button">
             <button class="form__button-sell" type="submit">出品する</button>
         </div>
     </form>       
@endsection