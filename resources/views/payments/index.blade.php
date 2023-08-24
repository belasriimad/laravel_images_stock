@extends('layouts.app')

@section('title')
    Pay order
@endsection

@section('styles')
<style>
    /* Variables */
    form {
        width: 30vw;
        min-width: 500px;
        align-self: center;
        box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
            0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
        border-radius: 7px;
        padding: 40px;
    }

    .hidden {
        display: none;
    }

    #payment-message {
        color: rgb(105, 115, 134);
        font-size: 16px;
        line-height: 20px;
        padding-top: 12px;
        text-align: center;
    }

    #payment-element {
        margin-bottom: 24px;
    }

    /* Buttons and links */
    button.stripe_button {
        background: #5469d4;
        font-family: Arial, sans-serif;
        color: #ffffff;
        border-radius: 4px;
        border: 0;
        padding: 12px 16px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: block;
        transition: all 0.2s ease;
        box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
        width: 100%;
    }
    button:hover {
        filter: contrast(115%);
    }
    button:disabled {
        opacity: 0.5;
        cursor: default;
    }

    /* spinner/processing state, errors */
    .spinner,
    .spinner:before,
    .spinner:after {
        border-radius: 50%;
    }
    .spinner {
        color: #ffffff;
        font-size: 22px;
        text-indent: -99999px;
        margin: 0px auto;
        position: relative;
        width: 20px;
        height: 20px;
        box-shadow: inset 0 0 0 2px;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
    }
    .spinner:before,
    .spinner:after {
        position: absolute;
        content: "";
    }
    .spinner:before {
        width: 10.4px;
        height: 20.4px;
        background: #5469d4;
        border-radius: 20.4px 0 0 20.4px;
        top: -0.2px;
        left: -0.2px;
        -webkit-transform-origin: 10.4px 10.2px;
        transform-origin: 10.4px 10.2px;
        -webkit-animation: loading 2s infinite ease 1.5s;
        animation: loading 2s infinite ease 1.5s;
    }
    .spinner:after {
        width: 10.4px;
        height: 10.2px;
        background: #5469d4;
        border-radius: 0 10.2px 10.2px 0;
        top: -0.1px;
        left: 10.2px;
        -webkit-transform-origin: 0px 10.2px;
        transform-origin: 0px 10.2px;
        -webkit-animation: loading 2s infinite ease;
        animation: loading 2s infinite ease;
    }

    @-webkit-keyframes loading {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes loading {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @media only screen and (max-width: 600px) {
        form {
            width: 80vw;
            min-width: initial;
        }
    }
</style>
@endsection

@section('content')
    <div class="row my-4">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Pay order</h3>
                </div>
                <div class="card-body">
                    <form id="payment-form">
                        @csrf
                        <input type="hidden" id="total" value="{{$photo->price}}">
                        <div id="payment-element"></div>
                        <button class="stripe_button" id="submit">
                            <div class="spinner hidden" id="spinner"></div>
                            <span id="button-text">Pay now</span>
                        </button>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("YOUR PUBLISHABLE KEY");
        const items = [
            { amount: document.getElementById('total').value } 
        ];

        let elements;

        initialize();
        checkStatus();

        document.querySelector('#payment-form').addEventListener('submit', handleSubmit);

        async function initialize() {
            const { clientSecret } = await fetch("http://127.0.0.1:8000/order/pay", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                },
                body: JSON.stringify({items}),
            }).then((r) => r.json());

            elements = stripe.elements({clientSecret});

            const paymentElement = elements.create("payment");
            paymentElement.mount("#payment-element");
        }

        async function handleSubmit(e)
        {
            e.preventDefault();
            setLoading(true);

            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: "http://127.0.0.1:8000/pay/success"
                }
            });

            if(error.type === 'card_error' || error.type === 'validation_error') {
                showMessage(error.message);
            }else {
                showMessage("An unexpected error occured.")
            }

            setLoading(false);
        }

        async function checkStatus()
        {
            const clientSecret = new URLSearchParams(window.location.search).get(
                "payment_intent_client_secret"
            );

            if(!clientSecret) {
                return;
            }

            const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

            switch(paymentIntent.status) {
                case "succeeded": 
                    showMessage("Payment succeeded");
                break;
                case "processing": 
                    showMessage("Your payment is processing");
                break;
                case "requires_payment_method": 
                    showMessage("Your payment was not successful, please try again.");
                break;
                default:
                    showMessage("something went wrong!");
                break;
            }
        }

        function showMessage(messageText)
        {
            const messageContainer = document.querySelector('#payment-message');
            messageContainer.classList.remove('hidden');
            messageContainer.textContent = messageText;

            setTimeout(() => {
                messageContainer.classList.add('hidden');
                messageText.textContent = '';
            }, 4000);
        }

        function setLoading(isLoading) {
            if(isLoading) {
                document.querySelector('#submit').disabled = true;
                document.querySelector('#spinner').classList.remove('hidden');
                document.querySelector('#button-text').classList.add('hidden');
            }else {
                document.querySelector('#submit').disabled = false;
                document.querySelector('#spinner').classList.add('hidden');
                document.querySelector('#button-text').classList.remove('hidden');
            }
        }
    </script>
@endsection