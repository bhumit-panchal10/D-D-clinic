@extends('layouts.app')

@section('title', 'Edit Prescription Template')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Edit Prescription Template</h4>

                    <a href="{{ route('prescription-templates.index') }}" class="btn btn-primary btn-sm">
                        Back
                    </a>
                </div>

                @include('common.alert')

                @include('prescription_templates._form')

            </div>
        </div>
    </div>

@endsection
