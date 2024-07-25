<div id="wallet-list" class="mb-4">
    <h3 class="d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-4">Danh sách ví</span>
        <a class="fs-6 text-decoration-none" href="{{ route('wallets.index') }}">Toàn bộ Ví</a>
    </h3>

    <div class="d-block position-relative overflow-hidden">
        <div id="wallet-swiper" class="swipper">
            <div class="swiper-wrapper">
                @foreach($wallets as $wallet)
                <div class="swiper-slide h-auto">
                    <a href="{{ route('wallets.show', $wallet->id) }}" class="card p-0 h-100 text-decoration-none d-block">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold fs-6">{{ $wallet->name }}</h5>
                            </div>

                            <hr />

                            @foreach($wallet->walletAccounts as $account)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-6 fw-light">{{ $account->name ?? $account->type_name }}</span>
                                <span class="fs-6 fw-bold {{ $account->balance > 0 ? 'text-success' : ($account->balance === 0 ? '' : 'text-danger') }}">
                                    {{ $account->balance_currency }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>
