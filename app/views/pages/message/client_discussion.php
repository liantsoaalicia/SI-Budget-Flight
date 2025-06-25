<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- En-tête du ticket -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0">
                                <i class="fas fa-ticket-alt me-2"></i>
                                Mon Ticket #<?= $ticketId ?>
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
                        <div class="col-md-8">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($ticket['subject']) ?></h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                Créé le <?= date('d/m/Y à H:i', strtotime($ticket['datec'])) ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <p class="mb-1"><strong>Priorité:</strong></p>
                            <span class="badge bg-<?= getPriorityBadgeClass($ticket['priority']) ?> fs-6">
                                <?= getPriorityLabel($ticket['priority']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Description initiale -->
                    <div class="mt-4">
                        <h6 class="text-secondary">
                            <i class="fas fa-file-alt me-1"></i>
                            Description de votre problème:
                        </h6>
                        <div class="border p-3 bg-light rounded">
                            <?= nl2br(htmlspecialchars($ticket['message'])) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone de discussion -->
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 text-secondary">
                        <i class="fas fa-comments me-2"></i>
                        Échanges avec le support
                    </h5>
                    <small class="text-muted">
                        Communiquez avec notre équipe support pour résoudre votre problème
                    </small>
                </div>
                <div class="card-body p-0">
                    <!-- Messages -->
                    <div id="messages-container" class="messages-container" style="height: 450px; overflow-y: auto; padding: 20px;">
                        <?php if (empty($messages)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-comment-dots fa-3x mb-3 text-info"></i>
                                <h6>Commencez la conversation</h6>
                                <p>Posez votre question ou décrivez votre problème ci-dessous</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="message mb-4 <?= $message['fk_user_author'] ? 'agent-message' : 'client-message' ?>">
                                    <div class="d-flex <?= $message['fk_user_author'] ? 'justify-content-start' : 'justify-content-end' ?>">
                                        <div class="message-bubble <?= $message['fk_user_author'] ? 'bg-white border' : 'bg-info text-white' ?>" 
                                             style="max-width: 75%; padding: 15px; border-radius: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                            
                                            <!-- Avatar et nom -->
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="avatar me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; <?= $message['fk_user_author'] ? 'background-color: #17a2b8; color: white;' : 'background-color: #6c757d; color: white;' ?>">
                                                    <i class="fas <?= $message['fk_user_author'] ? 'fa-headset' : 'fa-user' ?>" style="font-size: 14px;"></i>
                                                </div>
                                                <div>
                                                    <strong style="font-size: 0.9em;">
                                                        <?php if ($message['fk_user_author']): ?>
                                                            <?= htmlspecialchars($agents[$message['fk_user_author']]['firstname'] . ' ' . $agents[$message['fk_user_author']]['lastname']) ?>
                                                            <span class="badge bg-success ms-1" style="font-size: 0.7em;">Support</span>
                                                        <?php else: ?>
                                                            Vous
                                                            <span class="badge bg-secondary ms-1" style="font-size: 0.7em;">Client</span>
                                                        <?php endif; ?>
                                                    </strong>
                                                    <div style="font-size: 0.75em; <?= $message['fk_user_author'] ? 'color: #666;' : 'color: rgba(255,255,255,0.8);' ?>">
                                                        <?= date('d/m/Y à H:i', strtotime($message['datep'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="message-content">
                                                <?php 
                                                $cleanMessage = $message['note'];
                                                // Supprimer le préfixe [CLIENT] si présent
                                                if (strpos($cleanMessage, '[CLIENT] ') === 0) {
                                                    $cleanMessage = substr($cleanMessage, 9);
                                                }
                                                ?>
                                                <?= nl2br(htmlspecialchars($cleanMessage)) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <!-- Indicateur de réponse en cours -->
                        <div id="typing-indicator" class="text-muted small" style="display: none;">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                                L'équipe support est en train de répondre...
                            </div>
                        </div>
                    </div>

                    <!-- Zone de saisie -->
                    <div class="border-top p-3 bg-light">
                        <?php if ($ticket['status'] == 3): ?>
                            <!-- Ticket fermé -->
                            <div class="alert alert-info mb-0 text-center">
                                <i class="fas fa-lock me-2"></i>
                                Ce ticket est fermé. Vous ne pouvez plus envoyer de messages.
                                <br>
                                <small>Si vous avez un nouveau problème, veuillez créer un nouveau ticket.</small>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="/message/send/client" id="message-form">
                                <input type="hidden" name="ticket_id" value="<?= $ticketId ?>">
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="message-input" class="form-label">
                                            <i class="fas fa-edit me-1"></i>
                                            Votre message:
                                        </label>
                                        <textarea 
                                            name="message" 
                                            id="message-input"
                                            class="form-control" 
                                            rows="4" 
                                            placeholder="Décrivez votre question ou votre problème en détail..."
                                            required
                                            maxlength="2000"></textarea>
                                        <div class="form-text">
                                            <span id="char-count">0</span>/2000 caractères
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Notre équipe vous répondra dans les plus brefs délais
                                        </small>
                                        <button type="submit" class="btn btn-info btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Envoyer le message
                                        </button>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informations utiles -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-clock text-success fa-2x mb-2"></i>
                            <h6 class="card-title">Temps de réponse</h6>
                            <p class="card-text small text-muted">
                                Notre équipe répond généralement<br>
                                <strong>sous 24 heures</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-star text-warning fa-2x mb-2"></i>
                            <h6 class="card-title">Satisfaction</h6>
                            <p class="card-text small text-muted">
                                Une fois votre problème résolu,<br>
                                <strong>évaluez notre service</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript pour l'interface client -->
<script>
let lastMessageId = <?= !empty($messages) ? max(array_column($messages, 'id')) : 0 ?>;
let checkInterval;

// Auto-scroll vers le bas
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

// Compteur de caractères
function updateCharCount() {
    const textarea = document.getElementById('message-input');
    const counter = document.getElementById('char-count');
    const count = textarea.value.length;
    counter.textContent = count;
    
    if (count > 1800) {
        counter.style.color = '#dc3545';
    } else if (count > 1500) {
        counter.style.color = '#fd7e14';
    } else {
        counter.style.color = '#6c757d';
    }
}

// Vérifier les nouvelles réponses
function checkNewMessages() {
    fetch(`/message/api/new/<?= $ticketId ?>/${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.count > 0) {
                // Afficher l'indicateur qu'il y a de nouveaux messages
                showNewMessageNotification();
                // Recharger après un court délai
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        })
        .catch(error => console.error('Erreur:', error));
}

// Notification de nouveau message
function showNewMessageNotification() {
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; max-width: 300px;';
    notification.innerHTML = `
        <i class="fas fa-bell me-2"></i>
        <strong>Nouvelle réponse!</strong>
        <br><small>L'équipe support a répondu à votre ticket</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Gérer l'envoi du formulaire
document.getElementById('message-form')?.addEventListener('submit', function(e) {
    const messageInput = document.getElementById('message-input');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    if (!messageInput.value.trim()) {
        e.preventDefault();
        alert('Veuillez saisir un message');
        return;
    }
    
    // Désactiver le bouton pour éviter les doubles envois
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
});

// Raccourci clavier Ctrl+Entrée
document.getElementById('message-input')?.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') {
        document.getElementById('message-form').submit();
    }
});

// Compteur de caractères en temps réel
document.getElementById('message-input')?.addEventListener('input', updateCharCount);

// Sauvegarde automatique du brouillon
let draftTimer;
document.getElementById('message-input')?.addEventListener('input', function() {
    clearTimeout(draftTimer);
    draftTimer = setTimeout(() => {
        localStorage.setItem('ticket_draft_<?= $ticketId ?>', this.value);
    }, 1000);
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    updateCharCount();
    
    // Restaurer le brouillon
    const draft = localStorage.getItem('ticket_draft_<?= $ticketId ?>');
    if (draft && document.getElementById('message-input')) {
        document.getElementById('message-input').value = draft;
        updateCharCount();
    }
    
    // Vérifier les nouvelles réponses toutes les 10 secondes
    <?php if ($ticket['status'] != 3): ?>
    checkInterval = setInterval(checkNewMessages, 10000);
    <?php endif; ?>
});

// Nettoyer le brouillon après envoi réussi
<?php if (isset($_GET['sent']) && $_GET['sent'] == 'true'): ?>
localStorage.removeItem('ticket_draft_<?= $ticketId ?>');
<?php endif; ?>

// Arrêter la vérification quand on quitte la page
window.addEventListener('beforeunload', function() {
    if (checkInterval) {
        clearInterval(checkInterval);
    }
});
</script>

<style>
.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

.message-bubble {
    word-wrap: break-word;
    position: relative;
}

.client-message .message-bubble {
    border-bottom-right-radius: 8px !important;
}

.agent-message .message-bubble {
    border-bottom-left-radius: 8px !important;
}

.agent-message .message-bubble::before {
    content: '';
    position: absolute;
    bottom: -1px;
    left: -8px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 15px 15px;
    border-color: transparent transparent #ffffff transparent;
}

.client-message .message-bubble::before {
    content: '';
    position: absolute;
    bottom: -1px;
    right: -8px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 15px 15px 0;
    border-color: transparent #17a2b8 transparent transparent;
}

#message-input {
    resize: vertical;
    min-height: 100px;
    border-radius: 15px;
    border: 2px solid #e9ecef;
    transition: border-color 0.15s ease-in-out;
}

#message-input:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.card {
    border: none;
    border-radius: 15px;
}

.btn-info {
    border-radius: 25px;
    padding: 10px 25px;
}

.alert {
    border-radius: 10px;
}

.avatar {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .message-bubble {
        max-width: 90% !important;
    }
    
    .messages-container {
        height: 350px !important;
    }
}
</style>

<?php
// Réutiliser les mêmes fonctions utilitaires
function getStatusBadgeClass($status) {
    switch ($status) {
        case 0: return 'danger';
        case 1: return 'warning';
        case 2: return 'success';
        case 3: return 'secondary';
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
        case 1: return 'success';
        case 2: return 'warning';
        case 3: return 'danger';
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