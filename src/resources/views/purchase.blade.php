@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-form__content">
 <div class="purchase-form__group">
      <div class="purchase-form__item">
           <div class="purchase-form__image">
                <img src="{{ asset('storage/' . $item->image) }}">
           </div>
           <div class="item-name">{{ $item->name }}
                <div class="item-price">
                     <span class="item-price__symbol">¥</span>
                     <span class="item-price__value">{{ $item->price }}</span>
                </div>
           </div>
      </div>
      <div class="purchase-form__pay">
           <div class="purchase-form__label">
                支払方法
           </div>
           <div class="form__input--text">
                <select id="paymentSelect" name="payment" form="purchaseForm">
                     <option value="" disabled selected>選択してください</option>
                     <option value="konbini" {{ old('payment') == 'konbini' ? 'selected' : '' }}>
                          コンビニ払い
                     </option>
                     <option value="card" {{ old('payment') == 'card' ? 'selected' : '' }}>
                          カード払い
                     </option>
                </select>
           </div>
           <div class="form__error">
                @error('payment')
                     {{ $message }}
                @enderror
           </div>
      </div>
      <div class="purchase-form__shipping">
           <div class="purchase-form__shipping-group">
                <div class="purchase-form__label">
                     配送先
                </div>
                <a class="purchase-form__link" href="/purchase/address/{{ $item->id }}">
                     変更する
                </a>
           </div>
           <div class="purchase-form-adress">
                <p>〒{{ $address->post_code ?? $profile->post_code?? '' }}</p>
                <p>{{ $address->address ?? $profile->address ?? ''}}</p>
           </div>
           <div class="form__error">
                @error('address_id')
                     {{ $message }}
                @enderror
           </div>
      </div>
 </div>
    <div class="purchase-form__price-group">
      <div class="purchase-form__price">
           <div class="purchase-form__product-price">
                <span class="product-price__label">商品代金</span>
                <span class="product-price__price">¥{{ $item->price }}</span>
           </div>
           <div class="purchase-form__product-price">
                <span class="product-price__label">支払方法</span>
                <div class="product-price__price">
                     <div id="paymentResult" class="payment-result">
                          未選択
                     </div>
                </div>
           </div>
      </div>
      <form id="purchaseForm" method="POST" action="/purchase/{{ $item->id }}">
      @csrf
           <div class="purchase-form__button">
                <button class="form__button-buy"type="submit" form="purchaseForm" >購入する</button>
           </div>
            <input type="hidden" name="address_id" value="{{ $address?->id ?? '' }}">
      </form>
     </div>
     
</div>

<script>
const select = document.getElementById('paymentSelect');
const result = document.getElementById('paymentResult');

select.addEventListener('change', function () {

    if (this.value === 'konbini') {
        result.textContent = 'コンビニ払い';
    }

    else if (this.value === 'card') {
        result.textContent = 'カード払い';
    }

    else {
        result.textContent = '未選択';
    }

});
</script>
@endsection