@php
    use Illuminate\Support\Str;
    use App\Models\CookieConsent;

    $user = auth()->user();
    $sessionToken = request()->cookie('session_token') ?? Str::uuid()->toString();

    if (!$user) {
        \Cookie::queue(cookie('session_token', $sessionToken, 60 * 24 * 30));
    }

    $hasConsent = CookieConsent::where(function ($q) use ($user, $sessionToken) {
        if ($user) {
            $q->where('user_id', $user->id);
        } else {
            $q->where('session_token', $sessionToken);
        }
    })->exists();
@endphp

@if (!$hasConsent)
    <div id="cookie-consent-modal" class="cookie-modal">
        <div class="cookie-modal-content">
            <p>This site uses cookies to enhance your experience. Please accept or decline.</p>
            <div class="cookie-buttons">
                <button type="button" class="btn-accept" onclick="submitConsent('accepted')">Accept</button>
                <button type="button" class="btn-decline" onclick="submitConsent('declined')">Decline</button>
            </div>
        </div>
    </div>

    <style>
        .cookie-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .cookie-modal-content {
            background: #fff;
            color: #000;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
        }
        .cookie-buttons {
            margin-top: 1rem;
        }
        .cookie-buttons button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 5px;
        }
        .btn-accept {
            background-color: #28a745;
            color: white;
        }
        .btn-decline {
            background-color: #dc3545;
            color: white;
        }
    </style>

    <script>
        function submitConsent(status) {
            fetch("{{ route('cookie.consent') }}", {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                credentials: "same-origin",
                body: null
            }).then(response => {
                if (response.ok) {
                    document.getElementById('cookie-consent-modal').style.display = 'none';
                } else {
                    console.error('Consent not saved');
                }
            }).catch(error => {
                console.error('Request failed', error);
            });
        }
    </script>
@endif
