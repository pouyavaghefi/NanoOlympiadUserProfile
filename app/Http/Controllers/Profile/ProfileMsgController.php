<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Tag;
use App\Models\MessageReply;
use Session;
class ProfileMsgController extends Controller
{
    public function inbox()
    {
        $user = auth()->user();

        $messages = auth()->user()->receivedMessages()
            ->with('sender')
            ->orderByDesc('pinned')  // pinned=1 first
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('profile.messages.inbox', compact('messages'));
    }


    public function show($id)
    {
        $message = Message::with([
            'sender',                     // Who sent the message
            'replies.sender',            // Eager load replies with their sender
            'recipients'                 // Optional: load all recipients
        ])->findOrFail($id);

        // Mark this message as read for the current user if it's in the pivot table
        $message->recipients()
            ->wherePivot('user_id', auth()->id())
            ->updateExistingPivot(auth()->id(), ['read' => true]);

        return view('profile.messages.show', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        // Simulated validation (since GET can't use Laravel's validator cleanly)
        if (!$request->has('body') || empty($request->query('body'))) {
            return redirect()->back()->with('error', 'Reply is required');
        }

        $reply = MessageReply::create([
            'message_id' => $id,
            'sender_id' => auth()->id(),
            'reply' => $request->query('body'),
        ]);

        Session::put('messageReplied',$reply->id);

        return redirect()->back()->with('success', 'Message replied');
    }

    public function compose()
    {
        $tags = Tag::all();
        return view('profile.messages.compose', compact('tags'));
    }

    public function submitCompose(Request $request)
    {
        dd(2);
    }
}
