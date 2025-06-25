<?php
namespace app\controllers;

use app\models\MessageModel;
use app\models\TicketModel;
use app\models\AgentModel;
use app\models\ClientModel;
use Flight;

class MessageController {
    
    private $messageModel;
    private $ticketModel;
    private $agentModel;
    private $clientModel;

    public function __construct() {
        $this->messageModel = new MessageModel();
        $this->ticketModel = new TicketModel();
        $this->agentModel = new AgentModel();
        $this->clientModel = new ClientModel();
    }

    /**
     * Afficher la page de discussion pour un agent
     */
    public function showAgentDiscussion($ticketId) {
        // Récupérer les infos du ticket
        $ticketResponse = $this->ticketModel->getTicket($ticketId);
        if ($ticketResponse['status'] !== 200) {
            Flight::redirect('/ticket/list');
            return;
        }

        $ticket = $ticketResponse['data'];
        
        // Récupérer les messages
        $messagesResponse = $this->messageModel->getTicketMessagesDirect($ticketId);
        $messages = $messagesResponse['data'] ?? [];

        // Récupérer les infos du client
        $clientResponse = $this->clientModel->getClient($ticket['fk_soc']);
        $client = $clientResponse['data'] ?? null;

        // Récupérer les agents pour affichage des auteurs
        $agentsResponse = $this->agentModel->listAgents();
        $agents = $agentsResponse['data'] ?? [];
        
        // Créer un tableau associatif pour les agents
        $agentsById = [];
        foreach ($agents as $agent) {
            $agentsById[$agent['id']] = $agent;
        }

        Flight::render('template.php', [
            'pageTitle' => 'Discussion - Ticket #' . $ticketId,
            'view' => 'message/agent_discussion',
            'currentPage' => 'tickets',
            'ticket' => $ticket,
            'client' => $client,
            'messages' => $messages,
            'agents' => $agentsById,
            'ticketId' => $ticketId,
            'userType' => 'agent',
            'currentUserId' => $_SESSION['user_id'] ?? 1 // À adapter selon votre système d'auth
        ]);
    }

    /**
     * Afficher la page de discussion pour un client
     */
    public function showClientDiscussion($ticketId) {
        // Récupérer les infos du ticket
        $ticketResponse = $this->ticketModel->getTicket($ticketId);
        if ($ticketResponse['status'] !== 200) {
            Flight::redirect('/');
            return;
        }

        $ticket = $ticketResponse['data'];
        
        // Vérifier que le client a le droit de voir ce ticket
        $clientId = $_SESSION['client_id'] ?? null;
        if ($ticket['fk_soc'] != $clientId) {
            Flight::redirect('/');
            return;
        }

        // Récupérer les messages
        $messagesResponse = $this->messageModel->getTicketMessagesDirect($ticketId);
        $messages = $messagesResponse['data'] ?? [];

        // Récupérer les agents pour affichage des auteurs
        $agentsResponse = $this->agentModel->listAgents();
        $agents = $agentsResponse['data'] ?? [];
        
        $agentsById = [];
        foreach ($agents as $agent) {
            $agentsById[$agent['id']] = $agent;
        }

        Flight::render('template.php', [
            'pageTitle' => 'Mon ticket #' . $ticketId,
            'view' => 'message/client_discussion',
            'currentPage' => 'my_tickets',
            'ticket' => $ticket,
            'messages' => $messages,
            'agents' => $agentsById,
            'ticketId' => $ticketId,
            'userType' => 'client',
            'currentUserId' => $clientId
        ]);
    }

    /**
     * Envoyer un message (agent)
     */
    public function sendAgentMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flight::json(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $ticketId = $_POST['ticket_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $userId = $_SESSION['user_id'] ?? 1; // À adapter selon votre système d'auth

        if (empty($ticketId) || empty($message)) {
            Flight::json(['error' => 'Données manquantes'], 400);
            return;
        }

        // Créer le message
        $messageData = [
            'message' => $message,
            'fk_user' => $userId
        ];

        $result = $this->messageModel->createMessageDirect($ticketId, $messageData);

        if ($result['status'] === 200 || $result['status'] === 201) {
            // Mettre à jour la date de dernière activité du ticket
            $this->ticketModel->updateTicket($ticketId, [
                'date_last_msg_sent' => date('Y-m-d H:i:s')
            ]);

            Flight::redirect('/message/agent/' . $ticketId);
        } else {
            Flight::json(['error' => 'Erreur lors de l\'envoi du message'], 500);
        }
    }

    /**
     * Envoyer un message (client)
     */
    public function sendClientMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Flight::json(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $ticketId = $_POST['ticket_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $clientId = $_SESSION['client_id'] ?? null;

        if (empty($ticketId) || empty($message)) {
            Flight::json(['error' => 'Données manquantes'], 400);
            return;
        }

        // Vérifier que le client a le droit d'écrire sur ce ticket
        $ticketResponse = $this->ticketModel->getTicket($ticketId);
        if ($ticketResponse['status'] !== 200 || $ticketResponse['data']['fk_soc'] != $clientId) {
            Flight::json(['error' => 'Accès non autorisé'], 403);
            return;
        }

        // Créer le message (fk_user null pour les messages clients)
        $messageData = [
            'message' => '[CLIENT] ' . $message,
            'fk_user' => null
        ];

        $result = $this->messageModel->createMessageDirect($ticketId, $messageData);

        if ($result['status'] === 200 || $result['status'] === 201) {
            // Mettre à jour la date de dernière activité du ticket
            $this->ticketModel->updateTicket($ticketId, [
                'date_last_msg_sent' => date('Y-m-d H:i:s')
            ]);

            Flight::redirect('/message/client/' . $ticketId);
        } else {
            Flight::json(['error' => 'Erreur lors de l\'envoi du message'], 500);
        }
    }

    /**
     * API pour récupérer les nouveaux messages (AJAX)
     */
    public function getNewMessages($ticketId, $lastMessageId = 0) {
        $messagesResponse = $this->messageModel->getTicketMessagesDirect($ticketId);
        $allMessages = $messagesResponse['data'] ?? [];

        // Filtrer les messages plus récents que lastMessageId
        $newMessages = array_filter($allMessages, function($message) use ($lastMessageId) {
            return $message['id'] > $lastMessageId;
        });

        Flight::json([
            'status' => 'success',
            'messages' => array_values($newMessages),
            'count' => count($newMessages)
        ]);
    }

    /**
     * Marquer un ticket comme lu
     */
    public function markTicketAsRead($ticketId) {
        $userId = $_SESSION['user_id'] ?? $_SESSION['client_id'] ?? null;
        
        if ($userId) {
            $this->messageModel->markAsRead($ticketId, $userId);
        }

        Flight::json(['status' => 'success']);
    }
}