<nav>
    <ul class="header-nav">
        <li class="header-nav__item">
            @if (Auth::check())
            <form action="/logout" class="header-nav__form" method="post">
                @csrf
                <button class="header-nav__logout-btn">ログアウト</button>
            </form>
            @else
            <a href="/login" class="header-nav__login-btn">ログイン</a>
            @endif
        </li>
        <li class="header-nav__item">
            <a href="/mypage" class="header-nav__btn">マイページ</a>
        </li>
        <li class="header-nav__item">
            <a href="" class="sell-btn">出品</a>
        </li>
    </ul>
<nav>