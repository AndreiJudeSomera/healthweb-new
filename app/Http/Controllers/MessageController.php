<?php

namespace App\Http\Controllers;

use App\Models\ClinicStaff;
use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use App\Models\PatientRecord;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller {
  public function index(Request $request) {
    $user = Auth::user();

    $query = Conversation::with(['participants', 'messages' => fn($q) => $q->latest()]);

    $conversations = $query->whereHas('participants', fn($q) => $q->where('user_id', $user->id))->orderByDesc('updated_at')->get();

    $conversation = null;
    if ($request->has('conversation_id')) {
      $conversation = Conversation::with(['participants', 'messages.sender'])->find($request->query('conversation_id'));
    }
    $conversation = $conversation ?? $conversations->first() ?? null;

    return view('messages.message', compact('conversations', 'conversation'));
  }

  public function indexPatientView(Request $request) {
    $user = Auth::user();

    $query = Conversation::with(['participants', 'messages' => fn($q) => $q->latest()]);

    $conversations = $query->whereHas('participants', fn($q) => $q->where('user_id', $user->id))->orderByDesc('updated_at')->get();

    $conversation = null;
    if ($request->has('conversation_id')) {
      $conversation = Conversation::with(['participants', 'messages.sender'])->find($request->query('conversation_id'));
    }
    $conversation = $conversation ?? $conversations->first() ?? null;

    return view('mobilelayouts.messages.message', compact('conversations', 'conversation'));
  }

  /**
   * Mobile user-specific view; reuses same backend logic but returns mobile view.
   */
  public function userIndex(Request $request) {
    $user = Auth::user();

    $query = Conversation::with(['participants', 'messages' => fn($q) => $q->latest()]);

    $conversations = $query->whereHas('participants', fn($q) => $q->where('user_id', $user->id))->orderByDesc('updated_at')->get();

    $conversation = null;
    if ($request->has('conversation_id')) {
      $conversation = Conversation::with(['participants', 'messages.sender'])->find($request->query('conversation_id'));
    }
    $conversation = $conversation ?? $conversations->first() ?? null;

    return view('usermessages.mobilemessages', compact('conversations', 'conversation'));
  }

  /**
   * API: search users for messaging based on role and query
   */
  public function searchUsers(Request $request) {
    $request->validate(['q' => 'nullable|string']);
    $q = $request->query('q', '');
    $user = Auth::user();

    $isDoctor = Doctor::where('user_id', $user->id)->exists();
    $isSecretary = $user->role === 1;
    $isAdmin = $user->role === 2;

    $query = User::query();
    // exclude current user
    $query->where('id', '!=', $user->id);

    // apply search term
    if ($q) {
      $like = '%' . $q . '%';
      $query->where(function ($s) use ($like) {
        $s->where('username', 'like', $like)->orWhere('email', 'like', $like);
      });
    }

    // Role filtering based on the current user's permissions
    if (!($isDoctor || $isSecretary || $isAdmin)) {
      // Patient: do not include other patients (role 0). Only include secretaries, admins and doctors.
      $doctorIds = Doctor::pluck('user_id')->toArray();
      $query->where(function ($sub) use ($doctorIds) {
        $sub->whereIn('role', [1, 2])->orWhereIn('id', $doctorIds);
      });
    }

    $users = $query->limit(30)->get();
    $result = $users->map(function ($u) {
      return [
        'id' => $u->id,
        'username' => $u->username,
        'display_name' => $this->displayName($u),
        'email' => $u->email,
        'role' => $u->role,
        'role_label' => $this->roleLabel($u),
        'gender' => $this->patientGender($u),
        'is_doctor' => Doctor::where('user_id', $u->id)->exists(),
        'is_secretary' => Secretary::where('user_id', $u->id)->exists(),
      ];
    });

    return response()->json(['data' => $result]);
  }

  /**
   * API: list authenticated user's conversations
   */
  public function apiConversations(Request $request) {
    $user = Auth::user();

    $conversations = Conversation::with(['participants', 'messages' => fn($q) => $q->latest()])
      ->whereHas('messages')
      ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
      ->orderByDesc('updated_at')
      ->get();

    $payload = $conversations->map(function ($c) use ($user) {
      $last = $c->messages->first();
      $otherParticipants = $c->participants->filter(fn($p) => $p->id !== $user->id)->map(fn($p) => [
        'id' => $p->id,
        'username' => $p->username,
        'display_name' => $this->displayName($p),
        'role' => $p->role,
        'role_label' => $this->roleLabel($p),
        'gender' => $this->patientGender($p),
      ])->values();

      return [
        'id' => $c->id,
        'subject' => $c->subject,
        'last_message' => $last?->body,
        'last_at' => $last?->created_at,
        'participants' => $otherParticipants,
      ];
    });

    return response()->json(['data' => $payload]);
  }

  /**
   * API: get messages for a conversation
   */
  public function apiConversationMessages($id) {
    $user = Auth::user();
    $conversation = Conversation::with(['participants', 'messages' => fn($q) => $q->with('sender', 'recipient')])->findOrFail($id);
    if (!$conversation->participants->contains('id', $user->id)) {
      return response()->json(['message' => 'Forbidden'], 403);
    }

    return response()->json(['data' => $conversation->messages]);
  }

  /**
   * API: delete messages for the authenticated user in a conversation
   */
  public function apiDeleteConversationMessages($id) {
    $user = Auth::user();
    $conversation = Conversation::with('participants')->findOrFail($id);

    if (!$conversation->participants->contains('id', $user->id)) {
      return response()->json(['message' => 'Forbidden'], 403);
    }

    // Delete messages in this conversation where the user is sender or recipient
    $deleted = Message::where('conversation_id', $conversation->id)
      ->where(function ($q) use ($user) {
        $q->where('sender_id', $user->id)->orWhere('recipient_id', $user->id);
      })->delete();

    return response()->json(['message' => 'Deleted', 'count' => $deleted]);
  }

  private function displayName($user) {
    // Staff: use ClinicStaff Fname + Lname
    $staff = ClinicStaff::where('user_id', $user->id)->first();
    if ($staff) {
      $name = trim(($staff->Fname ?? '') . ' ' . ($staff->Lname ?? ''));
      if ($name) return $name;
    }
    // Patient: use PatientRecord first_name + last_name (encrypted)
    if ($user->role === 0) {
      $record = Patient::where('user_id', $user->id)->first()?->record;
      if ($record) {
        $name = trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? ''));
        if ($name) return $name;
      }
    }
    return $user->username;
  }

  private function patientGender($user) {
    if ($user->role !== 0) return null;
    return Patient::with('record')->where('user_id', $user->id)->first()?->record?->gender;
  }

  private function roleLabel($user) {
    if (Doctor::where('user_id', $user->id)->exists()) {
      return 'Doctor';
    }

    if (Secretary::where('user_id', $user->id)->exists()) {
      return 'Secretary';
    }

    return match ($user->role) {
      2 => 'Admin',
      1 => 'Secretary',
      default => 'Patient',
    };
  }

  /**
   * Show a specific conversation (admin, doctor, secretary can view any; others must be participant)
   */
  public function show($id) {
    $user = Auth::user();
    $conversation = Conversation::with(['participants', 'messages.sender'])->findOrFail($id);

    if (!$conversation->participants->contains('id', $user->id)) {
      abort(403, 'You are not allowed to access this conversation');
    }

    $conversations = Conversation::with(['participants', 'messages' => fn($q) => $q->latest()])
      ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
      ->orderByDesc('updated_at')
      ->get();

    return view('messages.message', compact('conversations', 'conversation'));
  }

  /**
   * API: return unread message count + recent unread messages for the current user.
   */
  public function unread() {
    $user = Auth::user();

    $unread = Message::where('recipient_id', $user->id)
      ->whereNull('read_at')
      ->with('sender:id,username')
      ->latest()
      ->take(10)
      ->get();

    return response()->json([
      'unread_count' => $unread->count(),
      'messages'     => $unread->map(fn($m) => [
        'id'              => $m->id,
        'conversation_id' => $m->conversation_id,
        'sender'          => $m->sender ? $this->displayName($m->sender) : 'Unknown',
        'preview'         => \Illuminate\Support\Str::limit($m->body, 60),
        'time'            => $m->created_at->diffForHumans(),
      ]),
    ]);
  }

  /**
   * API: mark a specific message as read.
   */
  public function markMessageRead($id) {
    $message = Message::where('id', $id)
      ->where('recipient_id', Auth::id())
      ->firstOrFail();

    $message->update(['read_at' => now()]);

    return response()->json(['ok' => true]);
  }

  /**
   * Store a message in an existing conversation or create a new one.
   */
  public function store(Request $request) {
    $request->validate([
      'recipient_id' => 'required|exists:users,id',
      'body' => 'required|string',
      'conversation_id' => 'nullable|exists:conversations,id',
      'subject' => 'nullable|string',
    ]);

    $sender = Auth::user();
    $recipient = User::findOrFail($request->recipient_id);

    // Authorization logic: Doctor or Secretary can message anyone; patient only to staff (role 1,2) or doctors
    $isSenderDoctor = Doctor::where('user_id', $sender->id)->exists();
    $isSenderSecretary = $sender->role === 1;
    $isSenderPatient = $sender->role === 0;
    $isRecipientDoctor = Doctor::where('user_id', $recipient->id)->exists();

    if (!($isSenderDoctor || $isSenderSecretary) && $isSenderPatient && !in_array($recipient->role, [1, 2]) && !$isRecipientDoctor) {
      return response()->json(['message' => 'You are not allowed to send a message to this user'], 403);
    }

    // Find or create conversation
    $conversation = null;
    if ($request->conversation_id) {
      $conversation = Conversation::find($request->conversation_id);
    }
    if (!$conversation) {
      $conversation = Conversation::create([
        'subject' => $request->subject,
      ]);
      $conversation->participants()->attach([$sender->id, $recipient->id]);
    } else {
      // ensure participants exist
      if (!$conversation->participants->contains($sender->id)) {
        $conversation->participants()->attach($sender->id);
      }
      if (!$conversation->participants->contains($recipient->id)) {
        $conversation->participants()->attach($recipient->id);
      }
    }

    $message = Message::create([
      'conversation_id' => $conversation->id,
      'sender_id' => $sender->id,
      'recipient_id' => $recipient->id,
      'body' => $request->body,
    ]);

    $conversation->touch();

    return response()->json(['message' => 'Message sent', 'data' => $message], 201);
  }

}
