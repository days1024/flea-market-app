<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>flea-market-app</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <img class="header__logo" src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="coachtech">
      @if(!request()->is('login') && !request()->is('register') && !request()->is('email') && !request()->is('email/verify'))
     <form class="search-form" method="GET" action="/">
       <input type="text" name="keyword" value="{{ request('keyword') }}"  placeholder="何をお探しですか?"/>
       @if(request('tab'))
        <input type="hidden" name="tab" value="{{ request('tab') }}">
       @endif
     </form>
     <form  class="form__button-logout" action="/logout" method="post">
       @csrf
       <button type="submit">ログアウト</button>
     </form>
     <a href="/mypage" class="form__button-mypage">
        <button type="submit">マイページ</button>
     </a>
     <a href="/sell" class="form__button-sell">
     <button type="submit">出品</button>
     </a>
     @endif
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>