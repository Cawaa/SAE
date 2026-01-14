<?php

namespace App\Controllers;

use App\Models\ConversationModel;
use App\Models\MessageModel;

// Contrôleur pour la gestion des messages dans les conversations.
class MessageController extends BaseController
{
    // Envoie un message dans une conversation spécifique.
    public function send(int $conversationId)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        // Récupère le contenu du message depuis la requête POST.
        $content = trim((string) $this->request->getPost('content'));

        // Vérifie que le message n'est pas vide.
        if ($content === '') {
            return redirect()->to('/conversations/' . $conversationId)
                ->with('error', 'Message vide.');
        }

        // Vérifie que l'utilisateur est bien participant de la conversation.
        $convModel = new ConversationModel();
        if (!$convModel->isParticipant($conversationId, $userId)) {
            return redirect()->to('/conversations')->with('error', 'Accès refusé.');
        }

        $msgModel = new MessageModel();
        $msgModel->insert([
            'conversation_id' => $conversationId,
            'sender_id'       => $userId,
            'content'         => $content,
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/conversations/' . $conversationId);
    }
}
