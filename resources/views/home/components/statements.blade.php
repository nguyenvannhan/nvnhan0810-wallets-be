<div id="wallet-list" class="mb-4">
    <h3 class="d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-4">Sao kê</span>
    </h3>

    <div class="d-block position-relative overflow-hidden">
        <div id="wallet-swiper" class="swipper">
            <div class="swiper-wrapper">
                @foreach($statements as $statement)
                <div class="swiper-slide h-auto">
                    <div class="card p-0 h-100 text-decoration-none d-block">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="fw-bold fs-6">{{ $statement->walletAccount->name . ' - ' . $statement->walletAccount->wallet->name }}</h5>
                            </div>

                            <hr class="my-2" />

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-6 fw-bold text-danger">{{ number_format($statement->value) }} VNĐ</span>
                                <form class="fs-6" method="POST" action="{{ route('wallets.credit_payment', ) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="statement_id" value="{{ $statement->id }}" />
                                    <button type="submit" class="btn btn-link">Thanh toán</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>
