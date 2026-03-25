@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Inbox</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($messages->isEmpty())
                        <p class="text-muted">You have no messages.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>Receiver</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Received</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($messages as $message)
                                    <tr class="{{ $message->pivot->read ? '' : 'table-warning' }} {{ $message->pinned ? 'table-info' : '' }}">
                                        <td>
                                            @if($message->pinned)
                                                <i class="icofont-pin-alt"></i>
                                            @endif
                                            {{ $message->sender->fullName() ?? 'Unknown' }}
                                        </td>
                                        <td>{{ $message->subject }}</td>
                                        <td>
            <span class="badge bg-{{
                $message->priority === 'critical' ? 'danger' :
                ($message->priority === 'important' ? 'warning' : 'secondary')
            }}">
                {{ ucfirst($message->priority) }}
            </span>
                                        </td>
                                        <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                                        <td>
            <span class="badge bg-{{ $message->pivot->read ? 'secondary' : 'success' }}">
                {{ $message->pivot->read ? 'Read' : 'Unread' }}
            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('profile.msg.show', $message->id) }}" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $messages->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
