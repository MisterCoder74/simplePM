<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <title>Gestione Team - Multi Team e Membri</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body, html { 
      height: 100%; 
      margin: 0; 
      padding: 0;
      background: linear-gradient(135deg, #667eea 0%, #ffc107 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .container { 
      max-width: 100%; 
      padding: 20px; 
    }
    
    .main-header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      margin-bottom: 30px;
      padding: 25px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .section-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: none;
    }
    
    .section-card h4, .section-card h5 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .form-control, .form-select {
      border-radius: 10px;
      border: 2px solid #e9ecef;
      padding: 12px 15px;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
      background: white;
    }
    
    .btn-warning {
      background: #ffc107;
      border: none;
      border-radius: 10px;
      padding: 12px 20px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-outline-secondary {
      border-radius: 8px;
      transition: all 0.3s ease;
      border: 2px solid #6c757d;
    }
    
    .btn-outline-secondary:hover {
      transform: translateY(-1px);
      box-shadow: 0 3px 10px rgba(108, 117, 125, 0.3);
    }
    
    .btn-danger {
      background: linear-gradient(45deg, #dc3545, #c82333);
      border: none;
      border-radius: 10px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }
    
    .members-list > div {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      border: none;
      border-radius: 12px;
      margin-bottom: 12px;
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border-left: 4px solid #ffc107;
    }
    
    .members-list > div:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      background: white;
    }
    
    .members-list > div span {
      word-break: break-word;
      font-weight: 500;
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .members-list > div span::before {
      content: "👤";
      font-size: 1.2em;
    }
    
    .btn-sm {
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.85rem;
      transition: all 0.3s ease;
    }
    
    .btn-outline-danger {
      border: 2px solid #dc3545;
      color: #dc3545;
      background: transparent;
    }
    
    .btn-outline-danger:hover {
      background: #dc3545;
      color: white;
      transform: translateY(-1px);
    }
    
    .form-floating {
      position: relative;
    }
    
    .form-floating label {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #6c757d;
    }
    
    .d-flex.gap-2, .d-flex.gap-3 {
      gap: 15px !important;
    }
    
    .team-stats {
      background: linear-gradient(45deg, #667eea, #764ba2);
      color: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      text-align: center;
    }
    
    .team-stats h6 {
      margin: 0;
      font-size: 0.9rem;
      opacity: 0.9;
    }
    
    .team-stats .display-6 {
      font-size: 2rem;
      font-weight: 700;
      margin: 0;
    }
    
    .fade-in {
      animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .member-item {
      animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #6c757d;
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.5;
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
    
    /* Responsive */
    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }
      
      .d-flex.gap-2, .d-flex.gap-3 {
        flex-direction: column;
      }
      
      .d-flex.gap-2 > *, .d-flex.gap-3 > * {
        width: 100% !important;
      }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="main-header">
          
    <div id="user-info" class="position-absolute top-0 end-0 d-flex align-items-center me-3 mb-3" style="display: none;">
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
    <br>          
          
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h1 class="mb-2">
          <i class="fas fa-users text-primary me-3"></i>
          Gestione Team
        </h1>
        <p class="text-muted mb-0">Organizza e gestisci i tuoi team di lavoro</p>
      </div>
      <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i>
        Torna indietro
      </a>
    </div>
  </div>

<div id="team-management">
  <!-- Nuovo team -->
  <div class="section-card">
    <h4><i class="fas fa-plus-circle text-primary"></i> Nuovo Team</h4>
    <form id="add-team-form" class="d-flex gap-3">
      <div class="form-floating flex-grow-1">
        <input type="text" id="new-team-name" class="form-control" placeholder="Nome del team" required />
        <label for="new-team-name"><i class="fas fa-tag"></i> Nome del team</label>
      </div>
      <button type="submit" class="btn btn-warning">
        <i class="fas fa-plus me-2"></i>Crea Team
      </button>
    </form>
  </div>

  <!-- Seleziona team -->
  <div class="section-card">
    <div class="mb-3">
      <label for="select-team" class="form-label">
        <i class="fas fa-list me-2"></i>Seleziona Team
      </label>
      <select id="select-team" class="form-select">
        <option value="">-- Nessun team selezionato --</option>
      </select>
    </div>
  </div>

  <!-- Lista membri team -->
  <div id="members-container" class="section-card fade-in" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="fas fa-users text-primary"></i> Membri di <span id="team-name-header" class="text-dark"></span></h5>
      <button id="delete-team-btn" class="btn btn-outline-danger btn-sm">
        <i class="fas fa-trash me-2"></i>Elimina Team
      </button>
    </div>
    
    <div class="members-list mb-4" id="members-list">
      <div class="empty-state">
        <i class="fas fa-user-friends"></i>
        <p>Nessun membro nel team. Aggiungi il primo membro!</p>
      </div>
    </div>
    
    <!-- Form per aggiungere membro con selezione da lista utenti -->
    <form id="add-member-form" class="mb-3">
      <div class="row g-3">
        <div class="col-md-8">
          <div class="form-floating">
            <select id="user-select" class="form-select" required>
              <option value="">-- Seleziona utente --</option>
            </select>
            <label for="user-select"><i class="fas fa-user"></i> Seleziona Utente</label>
          </div>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-warning h-100 w-100">
            <i class="fas fa-user-plus me-2"></i>Aggiungi al Team
          </button>
        </div>
      </div>
    </form>

    <!-- Pulsante per ricaricare lista utenti -->
    <div class="text-center">
      <button id="refresh-users-btn" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-sync-alt me-2"></i>Ricarica Lista Utenti
      </button>
    </div>
  </div>

  <!-- Gestione utenti registrati -->
  <div class="section-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="fas fa-users-cog text-primary"></i> Gestione Utenti Registrati</h5>
      <button id="refresh-registered-users" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-sync-alt me-2"></i>Ricarica
      </button>
    </div>
    
    <div id="registered-users-list" class="row g-3">
      <div class="col-12 text-center">
        <p class="text-muted">Caricamento utenti...</p>
      </div>
    </div>
  </div>
</div>
</div>
<script>
const apiUrl = 'api.php';

const selectTeamEl = document.getElementById('select-team');
const membersContainer = document.getElementById('members-container');
const membersList = document.getElementById('members-list');
const teamNameHeader = document.getElementById('team-name-header');
const newTeamForm = document.getElementById('add-team-form');
const newMemberForm = document.getElementById('add-member-form');
const newTeamNameInput = document.getElementById('new-team-name');
const userSelectEl = document.getElementById('user-select');
const deleteTeamBtn = document.getElementById('delete-team-btn');
const refreshUsersBtn = document.getElementById('refresh-users-btn');
const refreshRegisteredUsersBtn = document.getElementById('refresh-registered-users');
const registeredUsersList = document.getElementById('registered-users-list');

let teams = [];
let users = [];
let currentTeamIndex = null;

async function fetchJSON(data) {
  const res = await fetch(apiUrl, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
  });
  return res.json();
}

// Carica lista team
async function loadTeams() {
  const res = await fetchJSON({action: 'getTeams'});
  if(res.success){
    teams = res.data;
    populateTeamsSelect();
    // Se avevamo un team selezionato, aggiorniamo visualizzazione
    if(currentTeamIndex !== null && teams[currentTeamIndex]){
      selectTeamEl.value = teams[currentTeamIndex].name;
      displayTeamMembers(currentTeamIndex);
    } else {
      resetTeamView();
    }
  } else {
    alert('Errore caricando i team');
  }
}

// Carica lista utenti per assegnazione team
async function loadUsersForTeamAssignment() {
  const res = await fetchJSON({action: 'getUsersForTeamAssignment'});
  if(res.success){
    users = res.data;
    populateUserSelect();
  } else {
    alert('Errore caricando gli utenti');
    console.error('Errore API:', res.message);
  }
}

// Carica utenti registrati per la gestione
async function loadRegisteredUsers() {
  registeredUsersList.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Caricamento utenti...</p></div>';
  
  const res = await fetchJSON({action: 'getUsers'});
  if(res.success){
    displayRegisteredUsers(res.data);
  } else {
    registeredUsersList.innerHTML = '<div class="col-12 text-center"><p class="text-danger">Errore caricando gli utenti</p></div>';
  }
}

// Mostra utenti registrati
function displayRegisteredUsers(usersList) {
  if(usersList.length === 0) {
    registeredUsersList.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Nessun utente registrato</p></div>';
    return;
  }

  registeredUsersList.innerHTML = '';
  usersList.forEach(user => {
    const userCard = document.createElement('div');
    userCard.className = 'col-md-4 col-lg-2';
    userCard.innerHTML = `
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-title">${escapeHtml(user.fullName)}</h6>
          <p class="card-text">
            <small class="text-muted">@${escapeHtml(user.username)}</small><br>
            <small class="text-muted">${escapeHtml(user.email)}</small><br>
            <span class="badge ${user.is_active ? 'bg-success' : 'bg-secondary'}">
              ${user.is_active ? 'Attivo' : 'Inattivo'}
            </span>
          </p>
        </div>
      </div>
    `;
    registeredUsersList.appendChild(userCard);
  });
}

// Popola dropdown selezione team
function populateTeamsSelect(){
  selectTeamEl.innerHTML = '<option value="">-- Nessun team selezionato --</option>';
  teams.forEach((team, index)=> {
    let option = document.createElement('option');
    option.value = team.name;
    option.textContent = team.name;
    selectTeamEl.appendChild(option);
  });
}

// Popola dropdown selezione utenti
function populateUserSelect(){
  userSelectEl.innerHTML = '<option value="">-- Seleziona utente --</option>';
  users.forEach(user => {
    let option = document.createElement('option');
    option.value = user.username;
    option.textContent = `${user.fullName} (@${user.username})`;
    option.dataset.userId = user.id;
    option.dataset.email = user.email;
    userSelectEl.appendChild(option);
  });
}

// Mostra membri team selezionato
function displayTeamMembers(index){
  const team = teams[index];
  if(!team) return resetTeamView();

  currentTeamIndex = index;
  teamNameHeader.textContent = team.name;
  membersList.innerHTML = '';

  if(team.members.length === 0){
    membersList.innerHTML = `
      <div class="empty-state">
        <i class="fas fa-user-friends"></i>
        <p>Nessun membro nel team. Aggiungi il primo membro!</p>
      </div>
    `;
  } else {
    team.members.forEach((member, idx) => {
      const memberCard = document.createElement('div');
      memberCard.className = 'member-card';
      memberCard.innerHTML = `
        <div class="member-info">
          <i class="fas fa-user me-2"></i>
          <span>${escapeHtml(member)}</span>
        </div>
        <button class="btn btn-sm btn-outline-danger" aria-label="Rimuovi membro ${escapeHtml(member)}">
          <i class="fas fa-times"></i>Rimuovi membro
        </button>
      `;
      
      const removeBtn = memberCard.querySelector('button');
      removeBtn.addEventListener('click', () => removeMember(idx));
      
      membersList.appendChild(memberCard);
    });
  }
  membersContainer.style.display = 'block';
}

// Reset visualizzazione quando non selezionato
function resetTeamView(){
  currentTeamIndex = null;
  membersContainer.style.display = 'none';
  selectTeamEl.value = "";
}

// Rimuove membro da team chiamando api
async function removeMember(memberIndex){
  if(currentTeamIndex === null) return;
  if(!confirm(`Eliminare il membro: ${teams[currentTeamIndex].members[memberIndex]}?`)) return;
  
  const res = await fetchJSON({
    action: 'deleteTeamMember',
    teamName: teams[currentTeamIndex].name,
    memberIndex
  });
  
  if(res.success){
    teams[currentTeamIndex].members.splice(memberIndex,1);
    displayTeamMembers(currentTeamIndex);
  } else {
    alert(res.message || 'Errore rimuovendo membro');
  }
}

// Aggiungi membro a team tramite api (usando select utenti)
newMemberForm.addEventListener('submit', async e => {
  e.preventDefault();
  if(currentTeamIndex === null){
    alert('Seleziona un team');
    return;
  }
  
  const selectedUsername = userSelectEl.value.trim();
  if(selectedUsername === ''){
    alert('Seleziona un utente');
    return;
  }
  
  // Trova l'utente selezionato per ottenere il nome completo
  const selectedUser = users.find(user => user.username === selectedUsername);
  if(!selectedUser) {
    alert('Utente non trovato');
    return;
  }
  
  // Verifica se l'utente è già nel team
  if(teams[currentTeamIndex].members.includes(selectedUsername)) {
    alert('Questo utente è già nel team');
    return;
  }
  
  const res = await fetchJSON({
    action: 'addTeamMember',
    teamName: teams[currentTeamIndex].name,
    memberName: selectedUsername
  });
  
  if(res.success){
    teams[currentTeamIndex].members.push(selectedUsername);
    displayTeamMembers(currentTeamIndex);
    userSelectEl.value = '';
  } else {
    alert(res.message || 'Errore aggiungendo membro');
  }
});

// Cambio team selezionato
selectTeamEl.addEventListener('change', () => {
  const tname = selectTeamEl.value;
  const idx = teams.findIndex(t => t.name === tname);
  if(idx >= 0) {
    displayTeamMembers(idx);
  } else {
    resetTeamView();
  }
});

// Aggiungi nuovo team
newTeamForm.addEventListener('submit', async e => {
  e.preventDefault();
  const teamName = newTeamNameInput.value.trim();
  if(teamName === ''){
    alert('Inserisci il nome del team');
    return;
  }
  if(teams.some(t => t.name === teamName)){
    alert('Esiste già un team con questo nome');
    return;
  }
  const res = await fetchJSON({action:'addTeam', name: teamName});
  if(res.success){
    newTeamNameInput.value = '';
    await loadTeams();
    // Seleziono il team appena creato
    const idx = teams.findIndex(t => t.name === teamName);
    if(idx>=0){
      selectTeamEl.value = teamName;
      displayTeamMembers(idx);
    }
  } else {
    alert(res.message || 'Errore creando team');
  }
});

// Elimina team intero
deleteTeamBtn.addEventListener('click', async () => {
  if(currentTeamIndex === null) return;
  if(!confirm(`Eliminare il team "${teams[currentTeamIndex].name}" e tutti i suoi membri?`)) return;

  const res = await fetchJSON({action: 'deleteTeam', index: currentTeamIndex});
  if(res.success){
    await loadTeams();
    resetTeamView();
  } else {
    alert(res.message || 'Errore eliminando team');
  }
});

// Ricarica lista utenti
refreshUsersBtn.addEventListener('click', () => {
  loadUsersForTeamAssignment();
});

// Ricarica utenti registrati
refreshRegisteredUsersBtn.addEventListener('click', () => {
  loadRegisteredUsers();
});

// Funzione di utilità per escape HTML
function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}
        
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



// Se hai già un event listener per il caricamento, aggiungi la chiamata lì
window.addEventListener('load', function() {
    updateDashboardUserInfo();
});                
                        

document.addEventListener('DOMContentLoaded', () => {
loadTeams();
loadUsersForTeamAssignment();
loadRegisteredUsers();
    updateDashboardUserInfo();
    if (typeof checkUserPermissions === 'function') {
        checkUserPermissions(); // Se hai già questa funzione
    }        
});

</script>
</body>
</html>
