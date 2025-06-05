@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2 class="mb-4">All Feedbacks</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Sentiment</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->id }}</td>
                        <td>{{ $feedback->user_name ?? 'N/A' }}</td>
                        <td>{{ $feedback->comment }}</td>
                        <td>
                            @if($feedback->sentiment)
                                <span class="badge bg-{{ $feedback->sentiment === 'POSITIVE' ? 'success' : ($feedback->sentiment === 'NEGATIVE' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst(strtolower($feedback->sentiment)) }}
                                </span>
                            @else
                                <span class="text-muted">Pending</span>
                            @endif
                        </td>
                        <td>{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No feedbacks found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
