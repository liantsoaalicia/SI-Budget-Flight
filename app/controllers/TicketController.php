<?php 
namespace app\controllers;
use app\models\ClientModel;
use app\models\TicketModel;
use app\models\AgentModel;
use Flight;

class TicketController {
    private $clientModel;
    private $ticketModel;

    public function __construct() {
        $this->clientModel = new ClientModel();
        $this->ticketModel = new TicketModel();
    }

    public function redirectForm() {
        $tiers = $this->clientModel->listClients();
        // var_dump($tiers['data']);
        Flight::render('template.php', [
            'pageTitle' => 'Ajout ticket',
            'view' => 'ticket/form',
            'currentPage' => 'ajout_ticket',
            'sidebarCollapsed' => false,
            'tiers' => $tiers['data']
        ]);
    }

    public function ajout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketData = [
                'subject' => $_POST['titre'],
                'message' => $_POST['description'],
                'priority' => $this->ticketModel->convertPriority($_POST['priorite']),
                'fk_soc' => $_POST['tiers']
            ];

            // var_dump($ticketData);

            $result = $this->ticketModel->createTicket($ticketData);
            if ($result['status'] !== 200) {
                Flight::json(['error' => 'Erreur lors de la creation du ticket'], 500);
                return;
            }

            $ticketId = $result['ticket_id'];
            $ticketRef = $result['ticket_ref'];

            // UPLOAD
            if(!empty($_FILES['file']['tmp_name'])) {
                $fileResponse = $this->ticketModel->uploadFile($ticketId, $ticketRef, $_FILES['file']);
                Flight::json(['success' => 'Ticket créé avec succès'], 200); // dans le terminal
            }

            Flight::redirect('/ticket/ajout?success=true');
        }
    }

    public function showAssignmentList() {
        $ticketModel = new TicketModel();
        $agentModel = new AgentModel();

        $ticketsResponse = $ticketModel->listTickets();
        $agentsResponse = $agentModel->listAgents();

        // Filter tickets with no assigned user
        $tickets = array_filter($ticketsResponse['data'], function($ticket) {
            return empty($ticket['fk_user_assign']); // only unassigned tickets
        });

        $agents = $agentsResponse['data'];

        Flight::render('template.php', [
            'pageTitle' => 'Assigner un ticket',
            'view' => 'ticket/assign_list',
            'tickets' => $tickets,
            'agents' => $agents,
            'currentPage' => 'assign_ticket'
        ]);
    }
    
    public function assignAgentToTicket() {
        $ticketId = $_POST['ticket_id'];
        $agentId = $_POST['agent_id'];

        $ticketModel = new TicketModel();
        $result = $ticketModel->updateTicket($ticketId, ['fk_user_assign' => $agentId]);

        // Debug output for development (comment out in production)
        // echo "<pre>"; var_dump($result); exit;

        if ($result['status'] !== 200 && $result['status'] !== 201) {
            Flight::json(['error' => 'Erreur lors de l\'assignation du ticket'], 500);
            return;
        }

        Flight::redirect('/ticket/assign');
    }


    
}