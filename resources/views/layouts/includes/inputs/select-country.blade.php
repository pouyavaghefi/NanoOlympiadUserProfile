@php
use Illuminate\Support\Facades\DB;

$countries = DB::table('countries')
->orderBy('continent')
->orderBy('name')
->get()
->groupBy('continent');

$userCountryCode = old('country') ?? auth()->user()->member->country ?? '';
@endphp

<select name="country" id="country" class="form-control" required>
    <option value="">Select a country...</option>

    @foreach($countries as $continent => $continentCountries)
    @if ($continent) {{-- skip null continent values --}}
    <optgroup label="{{ $continent }}">
        @foreach($continentCountries as $country)
        <option value="{{ $country->code }}" data-code="{{ $country->phone_code }}"
                {{ $userCountryCode == $country->code ? 'selected' : '' }}>
            {{ $country->name }}
        </option>
        @endforeach
    </optgroup>
    @endif
    @endforeach
</select>
