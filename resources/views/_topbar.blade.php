<style>
    .topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
        background: #f8f9fa;
        border-bottom: 1px solid #e6e6e6;
    }

    .topbar .title {
        font-size: 18px;
        font-weight: 600;
    }

    .topbar .actions {
        display: flex;
        gap: 8px;
    }

    a.btn {
        padding: 6px 12px;
        border: 1px solid #cfcfcf;
        background: #fff;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        color: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    a.btn.active {
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.12);
        transform: translateY(-1px);
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }
</style>

<div class="topbar">
    <div class="title">Bookstore</div>
    <div class="actions" role="tablist" aria-label="Topbar actions">
        <a href="{{ route('index.books') }}" class="btn @isset($page) {{ $page == 'list' ? 'active' : '' }} @endisset" role="tab">List of Books</a>
        <a href="{{ route('index.top') }}" class="btn @isset($page) {{ $page == 'top' ? 'active' : '' }} @endisset" role="tab">Top 10 Author</a>
        <a href="{{ route('index.rating') }}" class="btn @isset($page) {{ $page == 'rating' ? 'active' : ''}} @endisset" role="tab">Rating</a>
    </div>
</div>