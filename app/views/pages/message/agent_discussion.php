<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête du ticket -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">
                                <i class="fas fa-ticket-alt me-2"></i>
                                Ticket #<?= $ticketId ?>
                            </h4>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-<?= getStatusBadgeClass($ticket['status']) ?> fs-6">
                                <?= getStatusLabel($ticket['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title"><?= htmlspecialchars($ticket['subject']) ?></h5>
                            <p><strong>Client:</strong> <?= htmlspecialchars($client['name'] ?? 'Client inconnu') ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($client['email'] ?? 'N/A') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Priorité:</strong> 
                                <span class="badge bg-<?= getPriorityBadgeClass($ticket['priority']) ?>">
                                    <?= getPriorityLabel($ticket['priority']) ?>
                                </span>
                            </p>
                            <p><strong>Créé le:</strong> <?= date('d/m/Y H:i', strtotime($ticket['datec'])) ?></p>
                            <p><strong>Assigné à:</strong> 
                                <?= $ticket['fk_user_assign'] ? htmlspecialchars($agents[$ticket['fk_user_assign']]['firstname'] . ' ' . $agents[$ticket['fk_user_assign']]['lastname']) : 'Non assigné' ?>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Description initiale -->
                    <div class="mt-3">
                        <h6>Description initiale:</h6>
                        <div class="border p-3 bg-light rounded">
                            <?= nl2br(htmlspecialchars($ticket['message'])) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone de discussion -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Discussion
                    </h5>
                </div>
                <div class="card-body p-0">
                    <!-- Messages -->
                    <div id="messages-container" class="messages-container" style="height: 400px; overflow-y: auto; padding: 15px;">
                        <?php if (empty($messages)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                                <p>Aucun message dans cette discussion</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="message mb-3 <?= $message['fk_user_author'] ? 'agent-message' : 'client-message' ?>">
                                    <div class="d-flex <?= $message['fk_user_author'] ? 'justify-content-end' : 'justify-content-start' ?>">
                                        <div class="message-bubble <?= $message['fk_user_author'] ? 'bg-primary text-white' : 'bg-light' ?>" 
                                             style="max-width: 70%; padding: 10px 15px; border-radius: 15px;">
                                            
                                            <div class="message-content">
                                                <?= nl2br(htmlspecialchars($message['note'])) ?>
                                            </div>
                                            
                                            <div class="message-meta mt-2" style="font-size: 0.8em; <?= $message['fk_user_author'] ? 'color: rgba(255,255,255,0.8);' : 'color: #666;' ?>">
                                                <strong>
                                                    <?php if ($message['fk_user_author']): ?>
                                                        <?= htmlspecialchars($agents[$message['fk_user_author']]['firstname'] . ' ' . $agents[$message['fk_user_author']]['lastname']) ?>
                                                        <span class="badge badge-sm bg-success ms-1">Agent</span>
                                                    <?php else: ?>
                                                        <?= htmlspecialchars($client['name'] ?? 'Client') ?>
                                                        <span class="badge badge-sm bg-info ms-1">Client</span>
                                                    <?php endif; ?>
                                                </strong>
                                                <span class="ms-2"><?= date('d/m/Y H:i', strtotime($message['datep'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Indicateur de frappe (optionnel) -->
                        <div id="typing-indicator" class="text-muted small" style="display: none;">
                            <i class="fas fa-circle-notch fa-spin me-1"></i>
                            Le client est en train d'écrire...
                        </div>
                    </div>

                    <!-- Zone de saisie -->
                    <div class="border-top p-3 bg-light">
                        <form method="POST" action="/message/send/agent" id="message-form">
                            <input type="hidden" name="ticket_id" value="<?= $ticketId ?>">
                            
                            <div class="row g-2">
                                <div class="col">
                                    <textarea 
                                        name="message" 
                                        class="form-control" 
                                        rows="3" 
                                        placeholder="Tapez votre réponse ici..."
                                        required
                                        id="message-input"></textarea>
                                </div>
                                <div class="col-auto d-flex flex-column">
                                    <button type="submit" class="btn btn-primary mb-2">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Envoyer
                                    </button>
                                    
                                    <!-- Boutons d'action -->
                                    <div class="btn-group-vertical">
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                onclick="updateTicketStatus(<?= $ticketId ?>, 2)">
                                            <i class="fas fa-check me-1"></i>
                                            Résoudre
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="updateTicketStatus(<?= $ticketId ?>, 3)">
                                            <i class="fas fa-times me-1"></i>
                                            Fermer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript pour l'interface temps réel -->
<script>
let lastMessageId = <?= !empty($messages) ? max(array_column($messages, 'id')) : 0 ?>;
let checkInterval;

// Auto-scroll vers le bas
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

// Vérifier les nouveaux messages
function checkNewMessages() {
    fetch(`/message/api/new/<?= $ticketId ?>/${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.count > 0) {
                // Recharger la page pour afficher les nouveaux messages
                location.reload();
            }
        })
        .catch(error => console.error('Erreur:', error));
}

// Mettre à jour le statut du ticket
function updateTicketStatus(ticketId, status) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/ticket/update-status';
    
    const ticketInput = document.createElement('input');
    ticketInput.type = 'hidden';
    ticketInput.name = 'ticket_id';
    ticketInput.value = ticketId;
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = status;
    
    form.appendChild(ticketInput);
    form.appendChild(statusInput);
    document.body.appendChild(form);
    form.submit();
}

// Gérer l'envoi du formulaire
document.getElementById('message-form').addEventListener('submit', function(e) {
    const messageInput = document.getElementById('message-input');
    if (!messageInput.value.trim()) {
        e.preventDefault();
        alert('Veuillez saisir un message');
        return;
    }
});

// Raccourci clavier Ctrl+Entrée
document.getElementById('message-input').addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') {
        document.getElementById('message-form').submit();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    
    // Vérifier les nouveaux messages toutes les 5 secondes
    checkInterval = setInterval(checkNewMessages, 5000);
});

// Arrêter la vérification quand on quitte la page
window.addEventListener('beforeunload', function() {
    if (checkInterval) {
        clearInterval(checkInterval);
    }
});
</script>

<style>
.messages-container::-webkit-scrollbar {
    width: 8px;
}

.messages-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.messages-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.message-bubble {
    word-wrap: break-word;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.agent-message .message-bubble {
    border-bottom-right-radius: 5px !important;
}

.client-message .message-bubble {
    border-bottom-left-radius: 5px !important;
    border: 1px solid #dee2e6;
}

#message-input {
    resize: vertical;
    min-height: 80px;
}

.btn-group-vertical .btn {
    font-size: 0.8em;
    padding: 0.25rem 0.5rem;
}
</style>

<?php
// Fonctions utilitaires pour l'affichage
function getStatusBadgeClass($status) {
    switch ($status) {
        case 0: return 'danger';  // Ouvert
        case 1: return 'warning'; // En cours
        case 2: return 'success'; // Résolu
        case 3: return 'secondary'; // Fermé
        default: return 'secondary';
    }
}

function getStatusLabel($status) {
    switch ($status) {
        case 0: return 'Ouvert';
        case 1: return 'En cours';
        case 2: return 'Résolu';
        case 3: return 'Fermé';
        default: return 'Inconnu';
    }
}

function getPriorityBadgeClass($priority) {
    switch ($priority) {
        case 1: return 'success';  // Basse
        case 2: return 'warning';  // Normale
        case 3: return 'danger';   // Haute
        default: return 'secondary';
    }
}

function getPriorityLabel($priority) {
    switch ($priority) {
        case 1: return 'Basse';
        case 2: return 'Normale';
        case 3: return 'Haute';
        default: return 'Non définie';
    }
}
?>