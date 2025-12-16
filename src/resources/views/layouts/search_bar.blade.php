<div class="search-bar">
    <form action="/" class="search-form" method="get">
        <input type="text" class="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">

        <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">
    </form>
</div>