@extends('app')

@section('title', 'Create Free Flight')

@section('content')
@foreach($bids as $bid)
{{ $bid->id }}
@endforeach
    <div class="card mb-3">
        <div class="card-body">
            <h4>New Flight</h4>
            <div class="text-muted">Create your next passenger or cargo charter here!</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="row">
                    <div class="col-12">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismisablle fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('freeflights.create') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="airline_id" class="form-label">Airline <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="airline_id" name="airline_id" required>
                                    <option value="">Select an airline...</option>
                                    @foreach($airlines as $airline)
                                        <option value="{{ $airline->id }}">
                                            {{ $airline->name }} ({{ $airline->icao }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="flight_number" class="form-label">Flight Number <span
                                        class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="flight_number" name="flight_number"
                                        value="{{ old('flight_number') }}" required maxlength="10">
                                    <button type="button" class="btn btn-outline-secondary" id="generateFlightNumber">
                                        <i class="fas fa-random"></i> Generate
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="flight_type" class="form-label">Flight Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select mb-3" id="flight_type" name="flight_type" required>
                                    <option value="">Select flight type...</option>
                                    @foreach($flightTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('flight_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8 d-flex flex-column">
                            <div class="mb-3">
                                <label for="dpt_airport_id" class="form-label">Departure Airport <span
                                        class="text-danger">*</span></label>
                                <select class="form-select airport-select" id="dpt_airport_id" name="dpt_airport_id"
                                    required data-live-search="true">
                                    <option value="">Select departure airport...</option>
                                    @foreach($airports as $airport)
                                        <option value="{{ $airport->id }}">
                                            {{ $airport->icao }} - {{ $airport->name }}, {{ $airport->location }},
                                            {{ $airport->country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="arr_airport_id" class="form-label">Arrival Airport <span
                                        class="text-danger">*</span></label>
                                <select class="form-select airport-select" id="arr_airport_id" name="arr_airport_id"
                                    required data-live-search="true">
                                    <option value="">Select arrival airport...</option>
                                    @foreach($airports as $airport)
                                        <option value="{{ $airport->id }}">
                                            {{ $airport->icao }} - {{ $airport->name }}, {{ $airport->location }},
                                            {{ $airport->country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-auto mb-3">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="fas fa-plus"></i> Create New Flight
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <div class="container">
                                                        <div class="row justify-content-center">
                                                            <div class="col-md-8">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h4><i class="fas fa-plane"></i> Create Free Flight</h4>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        @if(session('success'))
                                                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                                                {{ session('success') }}
                                                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                                            </div>
                                                                        @endif

                                                                        @if(session('error'))
                                                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                                                {{ session('error') }}
                                                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                                            </div>
                                                                        @endif

                                                                        @if($errors->any())
                                                                            <div class="alert alert-danger">
                                                                                <ul class="mb-0">
                                                                                    @foreach($errors->all() as $error)
                                                                                        <li>{{ $error }}</li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                        @endif

                                                                        <form method="POST" action="{{ route('freeflights.create') }}">
                                                                            @csrf

                                                                            <div class="mb-3">
                                                                                <label for="airline_id" class="form-label">Airline <span class="text-danger">*</span></label>
                                                                                <select class="form-select" id="airline_id" name="airline_id" required>
                                                                                    <option value="">Select an airline...</option>
                                                                                    @foreach($airlines as $airline)
                                                                                        <option value="{{ $airline->id }}" {{ old('airline_id') == $airline->id ? 'selected' : '' }}>
                                                                                            {{ $airline->name }} ({{ $airline->icao }})
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label for="flight_number" class="form-label">Flight Number <span class="text-danger">*</span></label>
                                                                                <div class="input-group">
                                                                                    <input type="text" class="form-control" id="flight_number" name="flight_number" 
                                                                                           value="{{ old('flight_number') }}" required maxlength="10">
                                                                                    <button type="button" class="btn btn-outline-secondary" id="generateFlightNumber">
                                                                                        <i class="fas fa-random"></i> Generate
                                                                                    </button>
                                                                                </div>
                                                                                <div class="form-text">Flight numbers 1-999 may include 1-2 letter suffix (A-ZZ)</div>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label for="flight_type" class="form-label">Flight Type <span class="text-danger">*</span></label>
                                                                                <select class="form-select" id="flight_type" name="flight_type" required>
                                                                                    <option value="">Select flight type...</option>
                                                                                    @foreach($flightTypes as $key => $label)
                                                                                        <option value="{{ $key }}" {{ old('flight_type') == $key ? 'selected' : '' }}>
                                                                                            {{ $label }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label for="dpt_airport_id" class="form-label">Departure Airport <span class="text-danger">*</span></label>
                                                                                <select class="form-select airport-select" id="dpt_airport_id" name="dpt_airport_id" required data-live-search="true">
                                                                                    <option value="">Select departure airport...</option>
                                                                                    @foreach($airports as $airport)
                                                                                        <option value="{{ $airport->id }}" {{ old('dpt_airport_id') == $airport->id ? 'selected' : '' }}>
                                                                                            {{ $airport->icao }} - {{ $airport->name }}, {{ $airport->location }}, {{ $airport->country }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label for="arr_airport_id" class="form-label">Arrival Airport <span class="text-danger">*</span></label>
                                                                                <select class="form-select airport-select" id="arr_airport_id" name="arr_airport_id" required data-live-search="true">
                                                                                    <option value="">Select arrival airport...</option>
                                                                                    @foreach($airports as $airport)
                                                                                        <option value="{{ $airport->id }}" {{ old('arr_airport_id') == $airport->id ? 'selected' : '' }}>
                                                                                            {{ $airport->icao }} - {{ $airport->name }}, {{ $airport->location }}, {{ $airport->country }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>

                                                                            <div class="d-grid">
                                                                                <button type="submit" class="btn btn-primary">
                                                                                    <i class="fas fa-plus"></i> Create Free Flight
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Generate initial flight number
            generateFlightNumber();

            // Generate flight number button click
            document.getElementById('generateFlightNumber').addEventListener('click', function () {
                generateFlightNumber();
            });

            function generateFlightNumber() {
                fetch('{{ route("freeflights.generate-flight-number") }}')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('flight_number').value = data.flight_number;
                    })
                    .catch(error => {
                        console.error('Error generating flight number:', error);
                    });
            }
        });
    </script>

@endsection
