<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Show all message conversations.
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get unique conversation partners with latest message
        $conversations = DB::table('messages')
            ->select(DB::raw('
                CASE 
                    WHEN sender_id = ' . $userId . ' THEN receiver_id
                    ELSE sender_id
                END as partner_id,
                MAX(created_at) as last_message_at,
                MAX(id) as last_message_id
            '))
            ->where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->groupBy('partner_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get partner details and last messages
        $conversations = $conversations->map(function($conversation) {
            $partner = User::find($conversation->partner_id);
            $lastMessage = Message::find($conversation->last_message_id);
            
            // Get unread count for this conversation
            $unreadCount = Message::where('sender_id', $conversation->partner_id)
                                 ->where('receiver_id', Auth::id())
                                 ->where('is_read', false)
                                 ->count();
            
            return (object)[
                'user' => $partner,
                'last_message' => $lastMessage,
                'last_message_at' => $conversation->last_message_at,
                'unread_count' => $unreadCount
            ];
        });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show conversation with a specific user.
     */
    public function show(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('messages.index')->with('error', 'You cannot message yourself!');
        }

        $messages = Message::where(function($query) use ($user) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark received messages as read
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true, 'read_at' => now()]);

        return view('messages.conversation', compact('user', 'messages'));
    }

    /**
     * Send a new message.
     */
    public function store(Request $request, User $receiver)
    {
        if ($receiver->id === Auth::id()) {
            return back()->with('error', 'You cannot message yourself!');
        }

        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Message sent successfully!');
    }

    /**
     * Get unread messages count.
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
                        ->where('is_read', false)
                        ->count();

        return response()->json(['count' => $count]);
    }
}
