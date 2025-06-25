<?php
namespace app\models;

class MessageModel extends AbstractDolibarrModel {

    /**
     * Créer un nouveau message dans un ticket
     */
    public function createMessage($ticketId, $messageData) {
        // Utilise l'endpoint des événements de ticket pour ajouter un message
        $endpoint = "tickets/{$ticketId}/events";
        
        $data = [
            'message' => $messageData['message'],
            'fk_user' => $messageData['fk_user'] ?? null, // ID de l'utilisateur (agent)
            'dateevent' => date('Y-m-d H:i:s'),
            'type_action' => 'AC_OTH_AUTO', // Type d'action pour message
            'elementtype' => 'ticket'
        ];

        return $this->makeRequest('POST', $endpoint, $data);
    }

    /**
     * Récupérer tous les messages d'un ticket
     */
    public function getTicketMessages($ticketId) {
        $endpoint = "tickets/{$ticketId}/events";
        return $this->makeRequest('GET', $endpoint);
    }

    /**
     * Alternative : utiliser la table des actions/événements directement
     * si l'API events ne fonctionne pas
     */
    public function createMessageDirect($ticketId, $messageData) {
        // Utilise l'endpoint des actions/événements
        $endpoint = "actioncomm";
        
        $data = [
            'label' => 'Message ticket',
            'note' => $messageData['message'],
            'datep' => date('Y-m-d H:i:s'),
            'fk_user_author' => $messageData['fk_user'] ?? null,
            'fk_element' => $ticketId,
            'elementtype' => 'ticket',
            'type_action' => 'AC_OTH_AUTO'
        ];

        return $this->makeRequest('POST', $endpoint, $data);
    }

    /**
     * Récupérer les messages via actioncomm
     */
    public function getTicketMessagesDirect($ticketId) {
        $endpoint = "actioncomm";
        $filters = [
            'fk_element' => $ticketId,
            'elementtype' => 'ticket'
        ];
        
        $response = $this->makeRequest('GET', $endpoint . '?' . http_build_query($filters));
        
        if ($response['status'] === 200 && isset($response['data'])) {
            // Trier par date
            usort($response['data'], function($a, $b) {
                return strtotime($a['datep']) - strtotime($b['datep']);
            });
        }
        
        return $response;
    }

public function markAsRead($messageId, $userId) {
        try {
            // Récupérer d'abord l'action pour vérifier qu'elle existe
            $getResponse = $this->makeRequest('GET', "actioncomm/{$messageId}");
            
            if ($getResponse['status'] !== 200) {
                return [
                    'status' => 404,
                    'message' => 'Message non trouvé'
                ];
            }

            // Récupérer les extrafields existants
            $extrafieldsEndpoint = "actioncomm/{$messageId}/extrafields";
            $extrafieldsResponse = $this->makeRequest('GET', $extrafieldsEndpoint);
            
            $existingExtrafields = [];
            if ($extrafieldsResponse['status'] === 200 && isset($extrafieldsResponse['data'])) {
                $existingExtrafields = $extrafieldsResponse['data'];
            }

            // Ajouter l'information de lecture
            $readByField = 'read_by_users';
            $readBy = $existingExtrafields[$readByField] ?? '';
            
            // Convertir en tableau les utilisateurs qui ont lu
            $readByUsers = !empty($readBy) ? explode(',', $readBy) : [];
            
            // Ajouter l'utilisateur s'il n'est pas déjà dans la liste
            if (!in_array($userId, $readByUsers)) {
                $readByUsers[] = $userId;
                
                // Mettre à jour les extrafields
                $updateData = [
                    $readByField => implode(',', $readByUsers),
                    'last_read_date' => date('Y-m-d H:i:s')
                ];
                
                $updateResponse = $this->makeRequest('PUT', $extrafieldsEndpoint, $updateData);
                
                if ($updateResponse['status'] === 200) {
                    return [
                        'status' => 200,
                        'message' => 'Message marqué comme lu',
                        'data' => [
                            'message_id' => $messageId,
                            'user_id' => $userId,
                            'read_at' => date('Y-m-d H:i:s')
                        ]
                    ];
                } else {
                    return [
                        'status' => 500,
                        'message' => 'Erreur lors de la mise à jour'
                    ];
                }
            }
            
            return [
                'status' => 200,
                'message' => 'Message déjà marqué comme lu',
                'data' => [
                    'message_id' => $messageId,
                    'user_id' => $userId
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'message' => 'Erreur : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtenir le nombre de messages non lus pour un ticket
     */
    public function getUnreadCount($ticketId, $userId) {
        try {
            // Récupérer tous les messages du ticket
            $messagesResponse = $this->getTicketMessagesDirect($ticketId);
            
            if ($messagesResponse['status'] !== 200 || !isset($messagesResponse['data'])) {
                return 0;
            }
            
            $unreadCount = 0;
            
            foreach ($messagesResponse['data'] as $message) {
                // Vérifier si l'utilisateur a lu ce message
                if (!$this->isMessageReadByUser($message['id'], $userId)) {
                    // Ne pas compter les messages de l'utilisateur lui-même comme non lus
                    if ($message['fk_user_author'] != $userId) {
                        $unreadCount++;
                    }
                }
            }
            
            return $unreadCount;
            
        } catch (\Exception $e) {
            error_log("Erreur getUnreadCount: " . $e->getMessage());
            return 0;
        }
    }

      /**
     * Vérifier si un message a été lu par un utilisateur spécifique
     */
    private function isMessageReadByUser($messageId, $userId) {
        try {
            $extrafieldsResponse = $this->makeRequest('GET', "actioncomm/{$messageId}/extrafields");
            
            if ($extrafieldsResponse['status'] === 200 && isset($extrafieldsResponse['data'])) {
                $extrafields = $extrafieldsResponse['data'];
                $readBy = $extrafields['read_by_users'] ?? '';
                
                if (!empty($readBy)) {
                    $readByUsers = explode(',', $readBy);
                    return in_array($userId, $readByUsers);
                }
            }
            
            return false;
            
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Marquer tous les messages d'un ticket comme lus pour un utilisateur
     */
    public function markAllAsRead($ticketId, $userId) {
        $messagesResponse = $this->getTicketMessagesDirect($ticketId);
        
        if ($messagesResponse['status'] !== 200 || !isset($messagesResponse['data'])) {
            return [
                'status' => 404,
                'message' => 'Aucun message trouvé pour ce ticket'
            ];
        }
        
        $results = [];
        foreach ($messagesResponse['data'] as $message) {
            $result = $this->markAsRead($message['id'], $userId);
            $results[] = [
                'message_id' => $message['id'],
                'status' => $result['status']
            ];
        }
        
        return [
            'status' => 200,
            'message' => 'Traitement terminé',
            'data' => $results
        ];
    }

    /**
     * Obtenir les statistiques de lecture pour un ticket
     */
    public function getReadingStats($ticketId) {
        $messagesResponse = $this->getTicketMessagesDirect($ticketId);
        
        if ($messagesResponse['status'] !== 200 || !isset($messagesResponse['data'])) {
            return [
                'total_messages' => 0,
                'messages_with_reads' => 0,
                'unique_readers' => 0
            ];
        }
        
        $totalMessages = count($messagesResponse['data']);
        $messagesWithReads = 0;
        $allReaders = [];
        
        foreach ($messagesResponse['data'] as $message) {
            $extrafieldsResponse = $this->makeRequest('GET', "actioncomm/{$message['id']}/extrafields");
            
            if ($extrafieldsResponse['status'] === 200 && isset($extrafieldsResponse['data'])) {
                $readBy = $extrafieldsResponse['data']['read_by_users'] ?? '';
                
                if (!empty($readBy)) {
                    $messagesWithReads++;
                    $readers = explode(',', $readBy);
                    $allReaders = array_merge($allReaders, $readers);
                }
            }
        }
        
        return [
            'total_messages' => $totalMessages,
            'messages_with_reads' => $messagesWithReads,
            'unique_readers' => count(array_unique($allReaders))
        ];
    }
}
