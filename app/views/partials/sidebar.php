<aside class="app-sidebar <?= ($sidebarCollapsed ?? false) ? 'collapsed' : '' ?>">
    <!-- <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-chevron-left"></i>
    </button> -->
    
    <ul class="sidebar">
        <li>
            <a href="/client/redirect" class="<?= ($currentPage ?? '') === 'ajout_client' ? 'active' : '' ?>">
                <i class="fas fa-project-diagram"></i>
                <span class="menu-label">Ajout client</span>
            </a>
        </li>

        <li>
            <a href="/ticket/redirect" class="<?= ($currentPage ?? '') === 'ajout_ticket' ? 'active' : '' ?>">
                <i class="fas fa-project-diagram"></i>
                <span class="menu-label">Creation de ticket</span>
            </a>
        </li>

        <li>
            <a href="/agent/form" class="<?= ($currentPage ?? '') === 'ajout_client' ? 'active' : '' ?>">
                <i class="fas fa-project-diagram"></i>
                <span class="menu-label">Ajout Agent</span>
            </a>
        </li>
        <li>
            <a href="/ticket/assign" class="<?= ($currentPage ?? '') === 'assign_ticket' ? 'active' : '' ?>">
                <i class="fas fa-user-check"></i>
                <span class="menu-label">Assigner un ticket</span>
            </a>
        </li>
        <li>
        <a href="/ticket/list" class="<?= ($currentPage ?? '') === 'list_tickets' ? 'active' : '' ?>">
            <i class="fas fa-ticket-alt"></i>
            <span class="menu-label">Liste des tickets</span>
        </a>
        </li>
       
</aside>