@extends('layouts.app')

@section('title', 'Prescription Template Master')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Prescription Template Master</h4>

                    <a href="{{ route('prescription-templates.create') }}" class="btn btn-primary">
                        Add Prescription Template
                    </a>
                </div>

                @include('common.alert')

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Prescription Template List</h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">Sr. No.</th>
                                        <th>Template Name</th>
                                        <th width="150">Total Medicines</th>
                                        <th width="120">Status</th>
                                        <th width="190">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($templates as $template)
                                        <tr>
                                            <td>
                                                {{ $templates->firstItem() + $loop->index }}
                                            </td>

                                            <td>
                                                {{ $template->template_name }}
                                            </td>

                                            <td>
                                                {{ $template->items_count }}
                                            </td>

                                            <td>
                                                @if ($template->is_active)
                                                    <span class="badge bg-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('prescription-templates.edit', $template->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>

                                                <form action="{{ route('prescription-templates.destroy', $template->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No prescription template found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $templates->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
