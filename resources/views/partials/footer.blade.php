<footer>
    <div class="row py-5">
        <div class="col-md-4">
            <img src="{{ asset('img/travesia.png') }}" alt="" height="24" width="118">
            <p class="custom-txt mt-3">Take you around the world with
                unforgettable experiences</p>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-2">
            <p class="fw-bold">Navigation</p>
            <p class="custom-txt">Destinations</p>
            <p class="custom-txt">Chat</p>
            <p class="custom-txt">My Ticket</p>
        </div>
        <div class="col-md-2">
            <p class="fw-bold">Resource</p>
            <p class="custom-txt">Laravel 8</p>
            <p class="custom-txt">MidTrans</p>
            <p class="custom-txt">Boostrap 5</p>
        </div>
        <div class="col-md-2">
            <p class="fw-bold">Other</p>
            @if (Auth::check() && Auth::user()->role === 'driver')
                <a href="{{ route('driver.register-driver') }}" class="custom-registerDriver">Register as a agent</a>
            @endif
        </div>
    </div>
</footer>
