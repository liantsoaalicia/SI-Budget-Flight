<aside class="app-sidebar <?= ($sidebarCollapsed ?? false) ? 'collapsed' : '' ?>">
    <!-- <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-chevron-left"></i>
    </button> -->
    
    <ul class="sidebar">
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'projets' ? 'active' : '' ?>">
                <i class="fas fa-project-diagram"></i>
                <span class="menu-label">Projets</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'budgets' ? 'active' : '' ?>">
                <i class="fas fa-wallet"></i>
                <span class="menu-label">Budgets</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'transactions' ? 'active' : '' ?>">
                <i class="fas fa-exchange-alt"></i>
                <span class="menu-label">Transactions</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'rapports' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-label">Rapports</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'equipe' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span class="menu-label">Équipe</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'categories' ? 'active' : '' ?>">
                <i class="fas fa-tags"></i>
                <span class="menu-label">Catégories</span>
            </a>
        </li>
        <li>
            <a href="#" class="<?= ($currentPage ?? '') === 'parametres' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i>
                <span class="menu-label">Paramètres</span>
            </a>
        </li>
    </ul>
</aside>