<footer class="bg-white position-fixed bottom-0 w-100 justify-content-evenly align-items-center">
    <div class="container d-flex gap-3 justify-content-evenly align-items-center">
        <div>
            <a href="{{ route('home') }}" class="footer-item">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div>
            <a href="{{ route('wallets.index') }}" class="footer-item">
                <i class="fa-solid fa-wallet"></i>
                <span>Wallet</span>
            </a>
        </div>
        <div>
            <a href="{{ route('transactions.create') }}" class="footer-item">
                <i class="fa-solid fa-circle-plus"></i>
                <span>Transaction</span>
            </a>
        </div>
        <div>
            <a href="{{ route('home') }}" class="footer-item">
                <i class="fa-solid fa-chart-simple"></i>
                <span>Analitycs</span>
            </a>
        </div>
        <div>
            <a href="{{ route('profile.index') }}" class="footer-item">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>
        </div>
    </div>
</footer>
