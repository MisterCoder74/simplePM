<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project Management Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <style>
    .card {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
      cursor: pointer;
      height: 100%;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .card-icon {
      font-size: 3rem;
      margin-bottom: 1rem;
    }
    .dashboard-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    position: relative;
    padding: 2rem 1rem;
      margin-bottom: 3rem;
      border-radius: 15px;
    }


.user-info {
    transition: all 0.3s ease;
}

.avatar-circle {
    transition: transform 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    border: 2px solid rgba(255,255,255,0.2);
}

.avatar-circle:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Responsive: layout per schermi piccoli */
@media (max-width: 768px) {
    .dashboard-header .position-absolute {
        position: static !important;
        margin: 0 !important;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    #user-info {
        display: none !important;
    }
    
    #mobile-user-info {
        display: block !important;
    }
}

/* Stili per i diversi livelli di badge */
.badge {
    font-size: 0.75em;
    padding: 0.35em 0.65em;
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Animazione per il pulsante logout */
.btn-danger {
    transition: all 0.3s ease;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}          
          
          
  </style>
</head>
<body class="bg-light">
  <div class="container mt-4">
    <!-- Header Section -->
<div class="dashboard-header text-center position-relative">
    <!-- Informazioni utente (posizionate in alto a destra) -->
    <div id="user-info" class="position-absolute top-0 end-0 d-flex align-items-center me-3 mt-3" style="display: none;">
        <div class="me-3 text-end">
            <div class="fw-bold text-dark" id="user-name">Nome Utente</div>
            <div class="small">
                <span class="badge" id="user-level-badge">user</span>
            </div>
        </div>
        <div class="avatar-circle d-flex align-items-center justify-content-center" 
             style="width: 40px; height: 40px; background-color: #007bff; border-radius: 50%; color: white; font-weight: bold;" 
             id="user-avatar">
            U
        </div>
    </div>

    <!-- Contenuto principale dell'header -->
    <h1 class="display-4 fw-bold mb-3">
        <i class="fas fa-project-diagram me-3"></i>
        SimplePM - Dashboard
    </h1>
    <p class="lead mb-4">Scegli il tool di cui hai bisogno per gestire i tuoi progetti</p>
    
    <!-- Sezione utente e logout centrata -->
    <div class="d-flex justify-content-center align-items-center gap-3 mt-3">
        <!-- Info utente compatta per mobile -->
        <div id="mobile-user-info" class="d-md-none text-center" style="display: none;">
            <div class="small text-muted">Benvenuto,</div>
            <div class="fw-bold" id="mobile-user-name">Nome Utente</div>
            <span class="badge" id="mobile-user-level">USER</span>
        </div>
        
        <!-- Pulsante di logout -->
        <button onclick="logout()" class="btn btn-danger">
            <i class="fas fa-sign-out-alt me-2"></i>
            Logout
        </button>
    </div>
</div>

    <!-- Tools Grid -->
    <div class="row g-4">
      <!-- Kanban Board -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='kanban.php'">
          <div class="card-body d-flex flex-column justify-content-center p-4">
            <div class="card-icon text-primary">
              <i class="fas fa-columns"></i>
            </div>
            <h5 class="card-title fw-bold text-primary mb-3">Kanban Board</h5>
            <p class="card-text text-muted">Organizza i task con il metodo Kanban. Visualizza il flusso di lavoro e gestisci le priorità.</p>
            <div class="mt-auto">
              <span class="badge bg-primary px-3 py-2">
                <i class="fas fa-arrow-right me-1"></i>
                Accedi
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Gantt Chart -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='gantt.php'">
          <div class="card-body d-flex flex-column justify-content-center p-4">
            <div class="card-icon text-info">
              <i class="fas fa-chart-gantt"></i>
            </div>
            <h5 class="card-title fw-bold text-info mb-3">Gantt Chart</h5>
            <p class="card-text text-muted">Pianifica progetti con timeline dettagliate. Monitora scadenze e dipendenze tra attività.</p>
            <div class="mt-auto">
              <span class="badge bg-info px-3 py-2">
                <i class="fas fa-arrow-right me-1"></i>
                Accedi
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Sticky Notes -->
      <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='notes.php'">
          <div class="card-body d-flex flex-column justify-content-center p-4">
            <div class="card-icon text-success">
              <i class="fas fa-sticky-note"></i>
            </div>
            <h5 class="card-title fw-bold text-success mb-3">Sticky Notes</h5>
            <p class="card-text text-muted">Annota idee e promemoria rapidi. Perfetto per brainstorming e note veloci.</p>
            <div class="mt-auto">
              <span class="badge bg-success px-3 py-2">
                <i class="fas fa-arrow-right me-1"></i>
                Accedi
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Team Management -->
      <!-- Sezione creazione e gestione Teams - visibile solo agli admin e manager -->      
      <div class="col-lg-3 col-md-6" id="teams-for-admins" style="display: none;">
        <div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='team.php'">
          <div class="card-body d-flex flex-column justify-content-center p-4">
            <div class="card-icon text-warning">
              <i class="fas fa-users"></i>
            </div>
            <h5 class="card-title fw-bold text-warning mb-3">Team Management</h5>
            <p class="card-text text-muted">Gestisci il tuo team e assegna ruoli. Coordina le persone e ottimizza la collaborazione.</p>
            <div class="mt-auto" >
              <span class="badge bg-warning px-3 py-2">
                <i class="fas fa-arrow-right me-1"></i>
                Accedi
              </span>
            </div>

        </div>
      </div>
    </div>
    <div class="row mt-5">



<!-- Issue Tracker (colore danger) -->
<div class="col-lg-3 col-md-6">
<div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='issues.php'">
<div class="card-body d-flex flex-column justify-content-center p-4">
<div class="card-icon text-danger">
<i class="fas fa-bug"></i>
</div>
<h5 class="card-title fw-bold text-danger mb-3">Issue Tracker</h5>
<p class="card-text text-muted">Gestisci bug, blocchi, richieste di miglioramento. Tieni traccia delle risoluzioni.</p>
<div class="mt-auto">
<span class="badge bg-danger px-3 py-2">
<i class="fas fa-arrow-right me-1"></i>
Apri
</span>
</div>
</div>
</div>
</div>

<!-- Documenti (colore secondary) -->
<div class="col-lg-3 col-md-6">
<div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='documents.php'">
<div class="card-body d-flex flex-column justify-content-center p-4">
<div class="card-icon text-secondary">
<i class="fas fa-folder-open"></i>
</div>
<h5 class="card-title fw-bold text-secondary mb-3">Documenti</h5>
<p class="card-text text-muted">Condividi file, gestisci versioni e documentazione di progetto in modo semplice.</p>
<div class="mt-auto">
<span class="badge bg-secondary px-3 py-2">
<i class="fas fa-arrow-right me-1"></i>
Apri
</span>
</div>
</div>
</div>
</div>

<!-- Calendario (colore info) -->
<div class="col-lg-3 col-md-6">
<div class="card border-0 shadow-sm text-center h-100" onclick="window.location.href='calendar.php'">
<div class="card-body d-flex flex-column justify-content-center p-4">
<div class="card-icon text-info">
<i class="fas fa-calendar-alt"></i>
</div>
<h5 class="card-title fw-bold text-info mb-3">Calendario</h5>
<p class="card-text text-muted">Visualizza riunioni, scadenze e attività del progetto in un calendario condiviso.</p>
<div class="mt-auto">
<span class="badge bg-info px-3 py-2">
<i class="fas fa-arrow-right me-1"></i>
Apri
</span>
</div>
</div>
</div>
</div>
</div>
           
          
    <!-- Stats Section -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <div class="row text-center">
              <div class="col-md-3">
                <div class="d-flex align-items-center justify-content-center">
                  <div class="text-primary me-3">
                    <i class="fas fa-tasks fa-2x"></i>
                  </div>
                  <div>
                    <h4 class="mb-0 fw-bold" id="numberoftools-admin">7</h4>
                    <small class="text-muted">Tools Disponibili</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="d-flex align-items-center justify-content-center">
                  <div class="text-success me-3">
                    <i class="fas fa-rocket fa-2x"></i>
                  </div>
                  <div>
                    <h4 class="mb-0 fw-bold">100%</h4>
                    <small class="text-muted">Produttività</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="d-flex align-items-center justify-content-center">
                  <div class="text-info me-3">
                    <i class="fas fa-clock fa-2x"></i>
                  </div>
                  <div>
                    <h4 class="mb-0 fw-bold">24/7</h4>
                    <small class="text-muted">Disponibilità</small>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="d-flex align-items-center justify-content-center">
                  <div class="text-warning me-3">
                    <i class="fas fa-star fa-2x"></i>
                  </div>
                  <div>
                    <h4 class="mb-0 fw-bold">Pro</h4>
                    <small class="text-muted">Qualità</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        <script>
// Funzione per aggiornare le informazioni utente nell'header
function updateUserInfoInHeader() {
    const currentUser = getCurrentUser();
    const userInfoDiv = document.getElementById('user-info');
    const userNameSpan = document.getElementById('user-name');
    const userLevelBadge = document.getElementById('user-level-badge');
    const userAvatar = document.getElementById('user-avatar');
    
    if (currentUser) {
        // Mostra la sezione info utente
        userInfoDiv.style.display = 'flex';
        
        // Imposta il nome
        userNameSpan.textContent = currentUser.fullName || currentUser.username;
        
        // Imposta il badge del livello con colori appropriati
        userLevelBadge.textContent = currentUser.level.toUpperCase();
        userLevelBadge.className = 'badge ' + getLevelBadgeClass(currentUser.level);
        
        // Imposta l'avatar con iniziali
        const initials = getInitials(currentUser.fullName || currentUser.username);
        userAvatar.textContent = initials;
        userAvatar.style.backgroundColor = getAvatarColor(currentUser.level);
        
    } else {
        // Nascondi la sezione se non c'è utente
        userInfoDiv.style.display = 'none';
    }
}

// Funzione per ottenere la classe CSS del badge in base al livello
function getLevelBadgeClass(level) {
    switch(level) {
        case 'admin':
            return 'bg-danger text-white'; // Rosso per admin
        case 'manager':
            return 'bg-warning text-dark'; // Giallo per manager
        case 'user':
        default:
            return 'bg-secondary text-white'; // Grigio per user
    }
}

// Funzione per ottenere il colore dell'avatar in base al livello
function getAvatarColor(level) {
    switch(level) {
        case 'admin':
            return '#dc3545'; // Rosso per admin
        case 'manager':
            return '#ffc107'; // Giallo per manager
        case 'user':
        default:
            return '#007bff'; // Blu per user
    }
}

// Funzione per estrarre le iniziali dal nome
function getInitials(fullName) {
    if (!fullName) return 'U';
    
    const names = fullName.trim().split(' ');
    if (names.length === 1) {
        return names[0].charAt(0).toUpperCase();
    }
    
    return (names[0].charAt(0) + names[names.length - 1].charAt(0)).toUpperCase();
}

// Funzione helper per ottenere l'utente corrente (se non già presente)
function getCurrentUser() {
    try {
        const userData = localStorage.getItem('currentUser');
        return userData ? JSON.parse(userData) : null;
    } catch (error) {
        console.error('Errore nel parsing dei dati utente:', error);
        return null;
    }
}
                
// Funzione per controllare i permessi utente
function checkUserPermissions() {
    const currentUser = getCurrentUser();
    
    if (!currentUser) {
        // Se non c'è utente loggato, reindirizza al login
        window.location.href = 'index.html';
        return;
    }
    
    const adminTeamsSection = document.getElementById('teams-for-admins');
     const numberOfTools = document.getElementById('numberoftools-admin');
            
    
    if (currentUser.level === 'admin' || currentUser.level === 'manager') {
        // Mostra sezione admin
        adminTeamsSection.style.display = 'block';
        numberOfTools.textContent = '7';
            
    } else {
        // Nascondi sezione admin e mostra messaggio (opzionale)
        adminTeamsSection.style.display = 'none';
        numberOfTools.textContent = '6';
         
    }
}


// Funzione per verificare se l'utente è admin
function isAdmin() {
    const currentUser = getCurrentUser();
    return currentUser && currentUser.level === 'admin';
}

// Funzione per verificare se l'utente è admin o manager
function isAdminOrManager() {
    const currentUser = getCurrentUser();
    return currentUser && (currentUser.level === 'admin' || currentUser.level === 'manager');
}

// Esegui il controllo permessi al caricamento della pagina
document.addEventListener('DOMContentLoaded', function() {
    checkUserPermissions();
});

// Se hai già un event listener per il caricamento, aggiungi la chiamata lì
window.addEventListener('load', function() {
    checkUserPermissions();
});        
        
                
                

// Funzione per aggiornare le informazioni utente nell'header della dashboard
function updateDashboardUserInfo() {
    const currentUser = getCurrentUser();
    const userInfoDiv = document.getElementById('user-info');
    const mobileUserInfoDiv = document.getElementById('mobile-user-info');
    const userNameSpan = document.getElementById('user-name');
    const mobileUserNameSpan = document.getElementById('mobile-user-name');
    const userLevelBadge = document.getElementById('user-level-badge');
    const mobileUserLevelBadge = document.getElementById('mobile-user-level');
    const userAvatar = document.getElementById('user-avatar');
    
    if (currentUser) {
        // Mostra le sezioni info utente
        if (userInfoDiv) userInfoDiv.style.display = 'flex';
        if (mobileUserInfoDiv) mobileUserInfoDiv.style.display = 'block';
        
        // Imposta il nome (desktop e mobile)
        if (userNameSpan) userNameSpan.textContent = currentUser.fullName || currentUser.username;
        if (mobileUserNameSpan) mobileUserNameSpan.textContent = currentUser.fullName || currentUser.username;
        
        // Imposta il badge del livello con colori appropriati
        const levelText = currentUser.level.toUpperCase();
        const badgeClass = getLevelBadgeClass(currentUser.level);
        
        if (userLevelBadge) {
            userLevelBadge.textContent = levelText;
            userLevelBadge.className = 'badge ' + badgeClass;
        }
        
        if (mobileUserLevelBadge) {
            mobileUserLevelBadge.textContent = levelText;
            mobileUserLevelBadge.className = 'badge ' + badgeClass;
        }
        
        // Imposta l'avatar con iniziali (solo desktop)
        if (userAvatar) {
            const initials = getInitials(currentUser.fullName || currentUser.username);
            userAvatar.textContent = initials;
            userAvatar.style.backgroundColor = getAvatarColor(currentUser.level);
        }
        
    } else {
        // Nascondi le sezioni se non c'è utente
        if (userInfoDiv) userInfoDiv.style.display = 'none';
        if (mobileUserInfoDiv) mobileUserInfoDiv.style.display = 'none';
    }
}

// Aggiorna le informazioni utente al caricamento della pagina
document.addEventListener('DOMContentLoaded', function() {
    updateDashboardUserInfo();
    if (typeof checkUserPermissions === 'function') {
        checkUserPermissions(); // Se hai già questa funzione
    }
});

// Se hai già un event listener per il caricamento, aggiungi la chiamata lì
window.addEventListener('load', function() {
    updateDashboardUserInfo();
});                
                
                
// --- Funzione di Logout ---
function logout() {
localStorage.removeItem('currentUser');
// Se hai variabili di sessione/cookie, cancellale qui
window.location.href = 'index.html'; // pagina di login
}
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>