<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak memiliki akses ke obrolan bimbingan.');
        }

        $conversations = Conversation::with(['dosen', 'mahasiswa', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->when($user->role === 'dosen', fn ($q) => $q->where('dosen_id', $user->id))
            ->when($user->role === 'mahasiswa', fn ($q) => $q->where('mahasiswa_id', $user->id))
            ->latest()
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->authorizeConversation($request, $conversation);

        $conversation->load(['dosen', 'mahasiswa']);
        $messages = $conversation->messages()->with('sender')->oldest()->get();
        $otherUser = $request->user()->id === $conversation->dosen_id
            ? $conversation->mahasiswa
            : $conversation->dosen;

        return view('chat.show', [
            'conversation' => $conversation,
            'messages' => $messages,
            'otherUser' => $otherUser,
            'reverb' => [
                'key' => config('broadcasting.connections.reverb.key'),
                'host' => config('broadcasting.connections.reverb.options.host'),
                'port' => config('broadcasting.connections.reverb.options.port'),
                'scheme' => config('broadcasting.connections.reverb.options.scheme'),
            ],
        ]);
    }

    public function store(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $this->authorizeConversation($request, $conversation);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
        ])->load('sender');

        broadcast(new MessageSent($message));

        $payload = $this->messagePayload($message);

        if ($request->expectsJson()) {
            return response()->json($payload, 201);
        }

        return back();
    }

    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorizeConversation($request, $conversation);

        $afterId = (int) $request->query('after_id', 0);

        $messages = $conversation->messages()
            ->with('sender')
            ->where('id', '>', $afterId)
            ->oldest()
            ->get()
            ->map(fn (Message $message) => $this->messagePayload($message));

        return response()->json($messages);
    }

    private function authorizeConversation(Request $request, Conversation $conversation): void
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            abort(403, 'Admin tidak memiliki akses ke obrolan bimbingan.');
        }

        if (! $conversation->hasUser($user)) {
            abort(403, 'Kamu tidak memiliki akses ke chat ini.');
        }
    }

    private function messagePayload(Message $message): array
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name,
            'sender_role' => $message->sender->role,
            'message' => $message->message,
            'time' => $message->created_at?->format('H:i'),
            'created_at' => $message->created_at?->toDateTimeString(),
        ];
    }
}
