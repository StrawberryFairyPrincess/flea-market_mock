@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
@endsection

@section('content')

    <div class="container">

        <div class="card">

            <div class="card-header">Stripe決済</div>

            <div class="card-body">
                <form id="card-form" action="{{ '/payment/' . $purchase['item_id'] }}" method="POST">
                    @csrf

                    <input type="hidden" name="price" value="{{ $price }}">
                    <input type="hidden" name="post_code" value="{{ $purchase['post_code'] }}">
                    <input type="hidden" name="address" value="{{ $purchase['address'] }}">
                    <input type="hidden" name="building" value="{{ $purchase['building'] }}">

                    <div class="card-form-element">
                        <label for="card_number">カード番号</label>
                        <div id="card-number" class="form-control"></div>
                    </div>

                    <div class="card-form-element">
                        <label for="card_expiry">有効期限</label>
                        <div id="card-expiry" class="form-control"></div>
                    </div>

                    <div class="card-form-element">
                        <label for="card-cvc">セキュリティコード</label>
                        <div id="card-cvc" class="form-control"></div>
                    </div>

                    <button class="card-form-button">支払い</button>

                    <div id="card-errors" class="text-danger"></div>
                </form>
            </div>

        </div>

    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        /* 基本設定*/
        const stripe_public_key = "{{ config('stripe.stripe_public_key') }}"
        const stripe = Stripe(stripe_public_key);
        const elements = stripe.elements();

        var cardNumber = elements.create('cardNumber');
        cardNumber.mount('#card-number');
        cardNumber.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var cardExpiry = elements.create('cardExpiry');
        cardExpiry.mount('#card-expiry');
        cardExpiry.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var cardCvc = elements.create('cardCvc');
        cardCvc.mount('#card-cvc');
        cardCvc.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('card-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var errorElement = document.getElementById('card-errors');
            if (event.error) {
                errorElement.textContent = event.error.message;
            } else {
                errorElement.textContent = '';
            }

            stripe.createToken(cardNumber).then(function(result) {
                if (result.error) {
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            var form = document.getElementById('card-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            form.submit();
        }
    </script>

@endsection