@extends('layouts.master')

@section('main-body')
    <div class="main px-lg-4 px-md-4">
        @include('layouts.includes.overalls.header')

        <div class="container mt-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Start a New Message</h5>
                    <a href="{{ route('profile.msg.inbox') }}" class="btn btn-sm btn-light">Back to Inbox</a>
                </div>

                <form action="{{ route('profile.msg.submit.compose') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        {{-- Subject --}}
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>

                        {{-- Priority --}}
                        <div class="mb-3">
                            <label for="priority" class="form-label fw-bold">Priority</label>
                            <select name="priority" id="priority" class="form-select" required>
                                <option value="normal">Normal</option>
                                <option value="important">Important</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>

                        {{-- Message Body --}}
                        <div class="mb-3">
                            <label for="body" class="form-label fw-bold">Message</label>
                            <textarea name="body" id="body" class="form-control" rows="6" required></textarea>
                        </div>

                        {{-- Attachment --}}
                        <div class="mb-3">
                            <label for="attachment" class="form-label fw-bold">Attachment (Optional)</label>
                            <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png,.zip,.txt">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Send Message</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
