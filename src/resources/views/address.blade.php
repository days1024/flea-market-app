@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection


@section('content')
<div class="profile-form">
 <div class="profile-form__content">
     <div class="profile-form__heading">
         <h1 class="profile-form__logo">住所の変更</h1>
     </div>
     <form class="form" action="/purchase/address/{{ $item->id }}" method="post" enctype="multipart/form-data">
         @csrf
         <div class="form__group">
             <div class="form__group-title">
                 <span class="form__label--item">郵便番号</span>
             </div>
             <div class="form__group-content">
                 <div class="form__input--text">
                     <input type="text" name="post_code"  value="{{ old('post_code', $address?->post_code ?? $profile?->post_code ?? '') }}"/>
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
                     <input type="text" name="address"  value="{{ old('address', $address?->address ?? $profile?->address ?? '') }}"/>
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
                     <input type="text" name="building"  value="{{ old('building', $address?->building ?? $profile?->building ?? '') }}"/>
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