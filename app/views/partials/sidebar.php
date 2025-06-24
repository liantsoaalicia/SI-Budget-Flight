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
       
</aside>