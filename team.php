<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <title>Gestione Team - Multi Team e Membri</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
      position: relative;
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
    
    .member-card {
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
        <h1 class="mb-2"><i class="fas fa-users text-primary me-3"></i>Gestione Team</h1>
        <p class="text-muted mb-0">Organizza e gestisci i tuoi team di lavoro</p>
      </div>
      <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left me-2"></i>Torna indietro
      </a>
    </div>
  </div>

<div id="team-management">
  <!-- Nuovo team (Manager+) -->
  <div class="section-card" id="add-team-section" style="display: none;">
    <h4><i class="fas fa-plus-circle text-primary"></i> Nuovo Team</h4>
    <form id="add-team-form" class="d-flex gap-3">
      <div class="form-floating flex-grow-1">
        <input type="text" id="new-team-name" class="form-control" placeholder="Nome del team" required />
        <label for="new-team-name"><i class="fas fa-tag"></i> Nome del team</label>
      </div>
      <button type="submit" class="btn btn-warning">Crea Team</button>
    </form>
  </div>

  <div class="section-card">
    <div class="mb-3">
      <label for="select-team" class="form-label"><i class="fas fa-list me-2"></i>Seleziona Team</label>
      <select id="select-team" class="form-select">
        <option value="">-- Nessun team selezionato --</option>
      </select>
    </div>
  </div>

  <div id="members-container" class="section-card" style="display:none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="fas fa-users text-primary"></i> Membri di <span id="team-name-header"></span></h5>
      <button id="delete-team-btn" class="btn btn-outline-danger btn-sm" style="display: none;">
        <i class="fas fa-trash me-2"></i>Elimina Team
      </button>
    </div>
    
    <div class="members-list mb-4" id="members-list"></div>
    
    <!-- Aggiunta membri (Manager+) -->
    <form id="add-member-form" class="mb-3" style="display: none;">
      <div class="row g-3">
        <div class="col-md-8">
          <select id="user-select" class="form-select" required>
            <option value="">-- Seleziona utente --</option>
          </select>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-warning w-100">Aggiungi al Team</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Gestione utenti registrati (Admin only) -->
  <div class="section-card" id="registered-users-section" style="display: none;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="fas fa-users-cog text-primary"></i> Gestione Utenti Registrati</h5>
      <button onclick="loadRegisteredUsers()" class="btn btn-outline-primary btn-sm">Ricarica</button>
    </div>
    <div id="registered-users-list" class="row g-3"></div>
  </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="auth.js"></script>
<script>
const selectTeamEl = document.getElementById('select-team');
const membersContainer = document.getElementById('members-container');
const membersList = document.getElementById('members-list');
const teamNameHeader = document.getElementById('team-name-header');
const newTeamForm = document.getElementById('add-team-form');
const newMemberForm = document.getElementById('add-member-form');
const userSelectEl = document.getElementById('user-select');
const deleteTeamBtn = document.getElementById('delete-team-btn');
const registeredUsersList = document.getElementById('registered-users-list');

let teams = [];
let users = [];
let currentTeamIndex = null;

async function loadTeams() {
    const res = await fetchWithAuth({action: 'getTeams'});
    if(res.success){
        teams = res.data;
        populateTeamsSelect();
    }
}

async function loadUsersForTeamAssignment() {
    const res = await fetchWithAuth({action: 'getUsersForTeamAssignment'});
    if(res.success){
        users = res.data;
        populateUserSelect();
    }
}

async function loadRegisteredUsers() {
    const res = await fetchWithAuth({action: 'getUsers'});
    if(res.success){
        registeredUsersList.innerHTML = '';
        res.data.forEach(user => {
            const card = document.createElement('div');
            card.className = 'col-md-3';
            card.innerHTML = `<div class="card h-100"><div class="card-body">
                <h6>${user.fullName}</h6>
                <small>@${user.username}</small><br>
                <small>${user.email}</small><br>
                <span class="badge ${getLevelBadgeClass(user.level)}">${user.level}</span>
            </div></div>`;
            registeredUsersList.appendChild(card);
        });
    }
}

function populateTeamsSelect(){
    selectTeamEl.innerHTML = '<option value="">-- Nessun team selezionato --</option>';
    teams.forEach(team => {
        const opt = document.createElement('option');
        opt.value = team.name;
        opt.textContent = team.name;
        selectTeamEl.appendChild(opt);
    });
}

function populateUserSelect(){
    userSelectEl.innerHTML = '<option value="">-- Seleziona utente --</option>';
    users.forEach(user => {
        const opt = document.createElement('option');
        opt.value = user.username;
        opt.textContent = `${user.fullName} (@${user.username})`;
        userSelectEl.appendChild(opt);
    });
}

selectTeamEl.addEventListener('change', () => {
    const idx = teams.findIndex(t => t.name === selectTeamEl.value);
    if(idx >= 0) displayTeamMembers(idx);
    else membersContainer.style.display = 'none';
});

function displayTeamMembers(index){
    const team = teams[index];
    currentTeamIndex = index;
    teamNameHeader.textContent = team.name;
    membersList.innerHTML = '';
    if(team.members.length === 0) membersList.innerHTML = '<p>Nessun membro.</p>';
    else {
        team.members.forEach((member, mIdx) => {
            const div = document.createElement('div');
            div.className = 'member-card';
            div.innerHTML = `<span>${member}</span>`;
            if (isAdminOrManager()) {
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm btn-outline-danger';
                btn.textContent = 'Rimuovi';
                btn.onclick = () => removeMember(mIdx);
                div.appendChild(btn);
            }
            membersList.appendChild(div);
        });
    }
    membersContainer.style.display = 'block';
}

async function removeMember(mIdx){
    if(!confirm('Rimuovere membro?')) return;
    const res = await fetchWithAuth({action: 'deleteTeamMember', teamName: teams[currentTeamIndex].name, memberIndex: mIdx});
    if(res.success) { loadTeams().then(() => displayTeamMembers(currentTeamIndex)); }
}

newTeamForm.addEventListener('submit', async e => {
    e.preventDefault();
    const name = document.getElementById('new-team-name').value;
    const res = await fetchWithAuth({action: 'addTeam', name});
    if(res.success) { e.target.reset(); loadTeams(); } else alert(res.message);
});

newMemberForm.addEventListener('submit', async e => {
    e.preventDefault();
    const memberName = userSelectEl.value;
    const res = await fetchWithAuth({action: 'addTeamMember', teamName: teams[currentTeamIndex].name, memberName});
    if(res.success) { loadTeams().then(() => displayTeamMembers(currentTeamIndex)); } else alert(res.message);
});

deleteTeamBtn.onclick = async () => {
    if(!confirm('Eliminare team?')) return;
    const res = await fetchWithAuth({action: 'deleteTeam', index: currentTeamIndex});
    if(res.success) { membersContainer.style.display = 'none'; loadTeams(); }
};

document.addEventListener('DOMContentLoaded', () => {
    updateDashboardUserInfo();
    if (isAdminOrManager()) {
        document.getElementById('add-team-section').style.display = 'block';
        document.getElementById('add-member-form').style.display = 'block';
        loadUsersForTeamAssignment();
    }
    if (isAdmin()) {
        deleteTeamBtn.style.display = 'block';
        document.getElementById('registered-users-section').style.display = 'block';
        loadRegisteredUsers();
    }
    loadTeams();
});

function updateDashboardUserInfo() {
    const currentUser = getCurrentUser();
    if (!currentUser) { window.location.href = 'index.html'; return; }
    const userInfoDiv = document.getElementById('user-info');
    userInfoDiv.style.display = 'flex';
    document.getElementById('user-name').textContent = currentUser.fullName || currentUser.username;
    const badge = document.getElementById('user-level-badge');
    badge.textContent = currentUser.level.toUpperCase();
    badge.className = 'badge ' + getLevelBadgeClass(currentUser.level);
    const avatar = document.getElementById('user-avatar');
    avatar.textContent = getInitials(currentUser.fullName || currentUser.username);
    avatar.style.backgroundColor = getAvatarColor(currentUser.level);
}
</script>
</body>
</html>
