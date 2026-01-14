<?php

namespace App\Controllers;

use App\Models\ConversationModel;
use App\Models\MessageModel;
use App\Models\BeatModel;
use CodeIgniter\Exceptions\PageNotFoundException;

// Contrôleur pour la gestion des conversations entre utilisateurs.
class ConversationsController extends BaseController
{
    // Affiche la liste des conversations de l'utilisateur connecté.
    public function index()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $convModel = new ConversationModel();
        $conversations = $convModel->listForUser($userId);

        return view('conversations/index', [
            'title' => 'Conversations',
            'conversations' => $conversations,
        ]);
    }

    // Affiche une conversation spécifique avec ses messages.
    public function show(int $conversationId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $convModel = new ConversationModel();
        if (!$convModel->isParticipant($conversationId, $userId)) {
            return redirect()->to('/conversations')->with('error', 'Accès refusé.');
        }

        $conversation = $convModel->find($conversationId);
        if (!$conversation) {
            throw new PageNotFoundException('Conversation introuvable.');
        }

        $msgModel = new MessageModel();
        $messages = $msgModel->findByConversation($conversationId);

        return view('conversations/show', [
            'title'        => 'Conversation',
            'conversation' => $conversation,
            'messages'     => $messages,
        ]);
    }

    // Démarre une nouvelle conversation à propos d'un beat.
    public function start(int $beatId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beat = $beatModel->find($beatId);

        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        $sellerId = (int)$beat['user_id'];

        if ($sellerId === $userId) {
            return redirect()->to('/beats/' . $beatId)
                ->with('error', 'Tu ne peux pas démarrer une conversation avec toi-même.');
        }

        $convModel = new ConversationModel();
        $convId = $convModel->getOrCreate($beatId, $userId, $sellerId);

        return redirect()->to('/conversations/' . $convId);
    }
}
