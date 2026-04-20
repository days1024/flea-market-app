@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection


@section('content')
<div class="sell-form__content">
    <div class="sell-form__heading">
        <a href="/?keyword={{ request('keyword') }}">おすすめ</a>
        <a href="/?tab=mylist&keyword={{ request('keyword') }}">マイリスト</a>
    </div>
    <div class="item-list">
    @if($tab === 'mylist')
        @foreach($likedItems as $item)
        <div class="item">
            @if ($item->buyer_id)
                <div class="item-image sold">
                    <img src="{{ asset('storage/' . $item->image) }}">
                    <div class="sold-label">SOLD</div>
                </div>
            @else
                <a href="/items/{{ $item->id }}">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image) }}">
                    </div>
                </a>
            @endif
            <p>{{ $item->name }}</p>
        </div>
        @endforeach

    @else
        @foreach($notLikedItems as $item)
        <div class="item">
            @if ($item->buyer_id)
                <div class="item-image sold">
                    <img src="{{ asset('storage/' . $item->image) }}">
                    <div class="sold-label">SOLD</div>
                </div>
            @else
                <a href="/items/{{ $item->id }}">
                    <div class="item-image">
                        <img src="{{ asset('storage/' . $item->image) }}">
                    </div>
                </a>
            @endif
            <p>{{ $item->name }}</p>
        </div>
        @endforeach
    @endif
    </div>
</div>

@endsection