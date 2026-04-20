@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection


@section('content')
<div class="profile-form">
 <div class="profile-form__content">
     <div class="profile-form__heading">
         <h1 class="profile-form__logo">プロフィール設定</h1>
     </div>
     <form class="form" action="/mypage/profile" method="post" enctype="multipart/form-data">
         @csrf
         <div class="form__group">
             <div class="form__group-content">
                 @csrf
                 @if(!empty($profile->profile_image))
                 <div class="item">
                     <img src="{{ asset('storage/' . $profile->profile_image) }}">
                 </div>
                 @endif
                 <label for="profile_image" class="form__button-image">画像を選択する
                 </label>
                 <input type="file" id="profile_image" name="profile_image" style="display:none;">
                 </div>
                 <div class="form__error">
                         @error('profile_image')
                             {{ $message }}
                         @enderror
                 </div>
             <div class="form__group-title">
                 <span class="form__label--item">ユーザー名</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="user_name" value="{{ old('user_name', $profile?->user_name) }}"/>
                      <div class="form__error">
                         @error('user_name')
                             {{ $message }}
                         @enderror
                     </div>
                 </div>
             </div>
             <div class="form__group-title">
                 <span class="form__label--item">郵便番号</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="post_code"  value="{{ old('post_code', $profile?->post_code) }}"/>
                     <div class="form__error">
                         @error('post_code')
                             {{ $message }}
                         @enderror
                     </div>
                 </div>
             </div>
             <div class="form__group-title">
                 <span class="form__label--item">住所</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="address"  value="{{ old('address', $profile?->address) }}"/>
                     <div class="form__error">
                         @error('address')
                             {{ $message }}
                         @enderror
                     </div>
                 </div>
             </div>
             <div class="form__group-title">
                 <span class="form__label--item">建物名</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="building"  value="{{ old('building', $profile?->building) }}"/>
                     <div class="form__error">
                         @error('password')
                             {{ $message }}
                         @enderror
                     </div>
                 </div>
             </div>
         </div>
         <div class="form__button">
             @csrf
             <button class="form__button-profile" type="submit">更新する</button>
         </div>
     </form>
 </div>
</div>
@endsection