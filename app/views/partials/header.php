<header class="app-header">
    <div class="header-brand">
        <button class="mobile-toggle" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <a href="/">
            <i class="fas fa-chart-line"></i>
            <span>Gestion Budgétaire</span>
        </a>
    </div>
    
    <nav class="header-nav">
        <ul>
            <li>
                <a href="/dashboard" class="<?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/profile" class="<?= ($currentPage ?? '') === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i>
                    <span class="nav-label">Mon Profil</span>
                </a>
            </li>
            <li>
                <a href="/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-label">Déconnexion</span>
                </a>
            </li>
        </ul>
    </nav>
</header>