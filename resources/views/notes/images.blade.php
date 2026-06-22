@extends('layouts.app')

@section('title', 'Note Images')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">Note Images for {{ $note->patient->name ?? 'Patient' }} -
                        {{ $note->treatment->treatment_name ?? 'Treatment' }}</h5>
                    <a href="{{ route('notes.index', $note->patient_id) }}" class="btn btn-sm btn-primary">Back to Notes</a>
                </div>

                @include('common.alert')

                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <strong>Date:</strong> {{ date('d-m-Y', strtotime($note->date)) }}<br>
                            <strong>Amount:</strong> {{ $note->amount }}<br>
                            <strong>Comments:</strong> {{ $note->comments ?? '-' }}
                        </div>

                        @if ($note->images->isEmpty())
                            <div class="alert alert-info">No images uploaded for this note.</div>
                        @else
                            <div class="row">
                                @foreach ($note->images as $image)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <img src="{{ asset($image->file_path) }}" class="card-img-top" alt="Note Image">
                                            <div class="card-body p-3">
                                                <p class="mb-2 text-truncate">{{ $image->filename }}</p>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <a href="{{ asset($image->file_path) }}" target="_blank"
                                                        class="btn btn-sm btn-secondary">View</a>
                                                    <form action="{{ route('notes.images.delete', $image->id) }}"
                                                        method="POST" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Delete this image?')">Delete</button>
                                                    </form>
                                                </div>
                                                <div class="text-muted small">
                                                    <strong>Tooth No:</strong> {{ $note->tooth_no ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
