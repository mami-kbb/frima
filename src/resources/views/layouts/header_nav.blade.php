<nav>
    <ul class="header-nav__content">
        <li class="header-nav__item">
            @if (Auth::check())
            <form action="/logout" class="header-nav__form" method="post">
                @csrf
                <button class="header-nav__btn">ログアウト</button>
            </form>
            @else
            <a href="/login" class="header-nav__btn">ログイン</a>
            @endif
        </li>
        <li class="header-nav__item">
            <a href="/mypage" class="header-nav__btn">マイページ</a>
        </li>
        <li class="header-nav__item">
            <a href="/sell" class="sell-btn">出品</a>
        </li>
    </ul>
<nav>