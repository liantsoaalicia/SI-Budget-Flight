<?php
namespace app\models;
use Flight;
use PDO;

class TicketModel extends AbstractDolibarrModel {

    public function createTicket($ticketData) {
        error_log("Données envoyées à l'API: " . json_encode($ticketData));
        $response = $this->makeRequest('POST', 'tickets', $ticketData);
        // Formatage cohérent de la réponse
        $ticketId = is_array($response['data']) ? ($response['data']['id'] ?? $response['data']) : $response['data'];
        
        return [
            'status' => $response['status'],
            'ticket_id' => $ticketId,
            'ticket_ref' => "TICKET_".$ticketId, // Génération de la référence
            'raw' => $response
        ];
    }

    public function deleteTicket($id) {
        return $this->makeRequest('DELETE', 'tickets/' . $id);
    }

    public function getTicket($id) {
        return $this->makeRequest('GET', 'tickets/' . $id);
    }

    public function updateTicket($id, $ticketData) {
        return $this->makeRequest('PUT', 'tickets/' . $id, $ticketData);
    }

    public function listTickets($filters = []) {
        $endpoint = 'tickets';
        if (!empty($filters)) {
            $endpoint .= '?' . http_build_query($filters);
        }
        return $this->makeRequest('GET', $endpoint);
    }

    public function convertPriority($priority) {
        $map = [
            'basse' => 1,
            'normale' => 2,
            'haute' => 3
        ];
        return $map[$priority] ?? 2;
    }

    public function uploadFile($ticketId, $ticketRef, $file) {
        $endpoint = 'documents/upload';
        $fileContent = base64_encode(file_get_contents($file['tmp_name']));

        $data = [
            "filecontent" => $fileContent,
            "filename" => $file['name'],
            "modulepart" => "ticket",
            "ref" => $ticketRef,
            "subdir" => "ticket/" . $ticketId
        ];

        return $this->makeRequest('POST', $endpoint, $data);
    }


}