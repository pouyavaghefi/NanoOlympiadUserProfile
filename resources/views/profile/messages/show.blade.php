@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Message Details</h5>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <a href="{{ route('profile.msg.inbox') }}" class="btn btn-sm btn-light">Back to Inbox</a>
                </div>
                <div class="card-body">
                    <h5 class="fw-bold">{{ $message->subject }}</h5>

                    <p class="text-muted mb-1">
                        <strong>From:</strong> {{ 'System' }} <br>
                        <strong>Receiver:</strong> {{ $message->sender->fullName() ?? 'Unknown' }} <br>
                        <strong>Received:</strong> {{ $message->created_at->format('F d, Y H:i') }}
                    </p>

                    <p>
                        <strong>Priority:</strong>
                        <span class="badge bg-{{
                            $message->priority === 'critical' ? 'danger' :
                            ($message->priority === 'important' ? 'warning' : 'secondary')
                        }}">
                            {{ ucfirst($message->priority) }}
                        </span>
                    </p>

                    <hr>

                    <div class="mb-3">
                        {!! nl2br(e($message->body)) !!}
                    </div>

                    @if($message->can_reply)
                        @if(!\Illuminate\Support\Facades\Session::has('messageReplied'))
                        <hr>
                        <form action="{{ route('profile.msg.reply', $message->id) }}" method="GET">
                            {{-- Do NOT use @csrf for GET --}}
                            <div class="mb-3">
                                <label for="replyBody" class="form-label fw-bold">Your Reply</label>
                                <textarea name="body" id="replyBody" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Send Reply</button>
                            </div>
                        </form>
                        @endif
                    @endif

                    @php
                        $filename = $message->attachment; // e.g. 'messages/hzaDuo5xhKiuxK99FAkUHHBYnt0Q62mRvlKfh628.txt'
                        $baseUrl = env('URL_ADMIN'); // e.g. https://admin.nanolympiad.org

                        // If $filename already includes 'messages/', just attach it after /storage/
                        $fileUrl = rtrim($baseUrl, '/') . '/storage/' . ltrim($filename, '/');
                    @endphp

                    @if(!is_null($message->attachment))
                        <a href="{{ $fileUrl }}" download class="btn btn-sm btn-outline-primary">
                            Download Attachment
                        </a>
                    @endif
                </div>
                @if($message->replies->count())
                    <hr>
                    <h6 class="fw-bold">&nbsp;&nbsp;&nbsp;Your Replies</h6><br>
                    <div class="list-group">
                        @foreach($message->replies->sortByDesc('created_at') as $reply)
                            <div class="list-group-item list-group-item-action mb-2 rounded border shadow-sm">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $reply->sender->fullName() ?? 'Unknown' }}</strong>
                                        <span class="text-muted small">({{ $reply->created_at->format('Y-m-d H:i') }})</span>
                                    </div>
                                    @if(!$reply->read)
                                        <span class="badge bg-info">New</span>
                                    @endif
                                </div>
                                <p class="mt-2 mb-0">{!! nl2br(e($reply->reply)) !!}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
