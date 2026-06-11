<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gantt Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    body, html { 
      height: 100%; 
      margin: 0; 
      background: linear-gradient(135deg, #667eea 0%, #0dcaf0 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }          
          
    body, html { margin: 0; height: 100%; }
    .container-fluid {
      margin-top: 10px;
      display: flex;
      flex-direction: column;
      user-select: none;
    }
    .main-header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      margin-bottom: 20px;
      padding: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }          
          
    h1 small a {
      font-size: 0.6em;
    }
    .controls {
      display: flex;
      gap: 15px;
      align-items: center;
      margin-bottom: 10px;
    }
    .gantt-wrapper {
      flex-grow: 1;
      overflow-x: auto;
      border: 1px solid #ddd;
      border-radius: 5px;
      background: #f9f9f9;
      padding: 10px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      position: relative;
    }
    .gantt-grid {
      display: grid;
      grid-template-columns: 150px repeat(var(--days-in-month), 40px);
    }
    .gantt-header {
      display: contents;
    }
    .gantt-header .cell {
      border-bottom: 2px solid #666;
      background: #ddd;
      text-align: center;
      font-weight: bold;
      padding: 6px 0;
      font-size: 12px;
      position: relative;
    }
    .gantt-header .cell:first-child {
      background: transparent;
      border: none;
    }
    .gantt-header .cell.weekend {
      background: #fbe8e6;
    }

    .gantt-row {
      display: contents;
    }
    .task-name-cell {
      border-bottom: 1px solid #bbb;
      background: #eaf1fb;
      padding: 5px 10px;
      vertical-align: middle;
      font-weight: 600;
      font-size: 12px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      border-right: 1px solid #bbb;
    }
.day-cell {
  /* Altezza aumentata per dare più spazio */
  height: 38px;  /* da 32px a 38 */
  width: 38px;      
  position: relative;
  background: #fff;
  border-bottom: 1px solid #bbb;
  border-left: 1px solid #ddd;
}
    .day-cell.weekend {
      background: #fbe8e6;
    }
    .day-cell:first-child {
      border-left: none;
    }

    /* Barra task */
    .task-bar {
      position: absolute;
      /* top: 6px; */
      left: 0;
      height: 22px;
      border-radius: 5px;
      color: white;
      text-align: center;
      font-size: 12px;
      white-space: nowrap;
      padding: 0 6px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      cursor: pointer;
      box-sizing: border-box;
      box-shadow: 0 1px 3px rgba(0,0,0,0.4);
    }
    .task-bar:hover {
      filter: brightness(0.9);
    }
    .task-bar .team-label {
      font-size: 9px;
      font-weight: 400;
      opacity: 0.85;
      margin-top: 6px;
      white-space: normal;
    }
            
    .task-bar > div {
    line-height: 11px; /* Metà dell'altezza della barra */
    margin: 0;
    padding: 0;
}        
          
.task-card {
    margin-bottom: 15px;
    min-height: 50px;
}

.gantt-bar {
    display: block;
    margin-bottom: 8px;
    padding: 4px 8px;
}

.task-card small {
    display: block;
    margin-top: 10px;
    color: #555;
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
            
          

    /* Scrollbar (opzionale) */
    .gantt-wrapper::-webkit-scrollbar {
      height: 10px;
    }
    .gantt-wrapper::-webkit-scrollbar-thumb {
      background: #aaa;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<div class="container-fluid mt-3">
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
          <i class="fas fa-columns text-primary me-3"></i>
          Gantt Chart
        </h1>
        <p class="text-muted mb-0">Organizza il workflow del tuo lavoro</p>
        <a class="btn btn-info btn-sm" href="gantt_manuale.html" target="_blank"><small>Manuale Operativo</small></a>      
      </div>
      <a href="dashboard.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Dashboard
      </a>
    </div>
  </div>
  <div role="navigation" aria-label="Controlli mese" class="controls mb-2">
    <button id="prev-month" class="btn btn-info btn-sm" aria-label="Mese precedente">&lt; Mese prec.</button>
    <div><strong id="month-label" aria-live="polite" aria-atomic="true"></strong></div>
    <button id="next-month" class="btn btn-info btn-sm" aria-label="Mese successivo">Mese succ. &gt;</button>
  </div>

  <div class="gantt-wrapper" id="gantt-wrapper" role="table" aria-label="Gantt chart tasks">
    <div class="gantt-grid" id="gantt-grid" role="grid"></div>
  </div>

  <hr/>
<!-- Sezione aggiunta task - visibile solo agli admin -->
<div id="admin-task-section" style="display: none;">
    <h4 class="mt-4 mb-3">Aggiungi nuovo task</h4>
    <div class="card shadow-sm border-info mb-4">
        <div class="card-body p-4" style="background-color: #d1ecf1;">
            <form id="gantt-form" class="row g-3 align-items-end" aria-label="Modulo aggiunta task gantt">
                <div class="col-md-4">
                    <label for="gantt-title" class="form-label mb-1">Titolo Task</label>
                    <input type="text" id="gantt-title" class="form-control" placeholder="Titolo task" required aria-required="true" aria-label="Titolo task"/>
                </div>
                <div class="col-md-2">
                    <label for="gantt-start" class="form-label mb-1">Data Inizio</label>
                    <input type="date" id="gantt-start" class="form-control" required aria-required="true" aria-label="Data inizio"/>
                </div>
                <div class="col-md-2">
                    <label for="gantt-end" class="form-label mb-1">Data Fine</label>
                    <input type="date" id="gantt-end" class="form-control" required aria-required="true" aria-label="Data fine"/>
                </div>
                <div class="col-md-3">
                    <label for="gantt-team" class="form-label mb-1">Seleziona Team</label>
                    <select id="gantt-team" class="form-select" required aria-required="true" aria-label="Seleziona team">
                        <option value="">Seleziona team</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-info btn-block">Aggiungi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Messaggio per utenti non admin (opzionale) -->
<div id="user-restriction-message" style="display: none;">
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        Solo gli amministratori e i manager possono aggiungere o modificare i task.
    </div>
</div>        
        
</div>

<!-- Modal modifica identico -->
<div class="modal fade" id="ganttEditModal" tabindex="-1" aria-labelledby="ganttEditModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" id="gantt-edit-form" aria-label="Modulo modifica task gantt">
      <div class="modal-header">
        <h5 class="modal-title" id="ganttEditModalLabel">Modifica task Gantt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="gantt-edit-index" />
        <div class="mb-3">
          <label for="gantt-edit-title" class="form-label">Titolo</label>
          <input id="gantt-edit-title" type="text" class="form-control" required aria-required="true" />
        </div>
        <div class="mb-3">
          <label for="gantt-edit-start" class="form-label">Data Inizio</label>
          <input type="date" id="gantt-edit-start" class="form-control" required aria-required="true" />
        </div>
        <div class="mb-3">
          <label for="gantt-edit-end" class="form-label">Data Fine</label>
          <input type="date" id="gantt-edit-end" class="form-control" required aria-required="true" />
        </div>
        <div class="mb-3">
          <label for="gantt-edit-team" class="form-label">Team</label>
          <select id="gantt-edit-team" class="form-select" required aria-required="true"></select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" type="button">Annulla</button>
        <button class="btn btn-primary btn-sm" type="submit">Salva</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

// Variabili globali
const ganttGrid = document.getElementById('gantt-grid');
const ganttWrapper = document.getElementById('gantt-wrapper');
const monthLabel = document.getElementById('month-label');
const prevMonthBtn = document.getElementById('prev-month');
const nextMonthBtn = document.getElementById('next-month');
const ganttEditModal = new bootstrap.Modal(document.getElementById('ganttEditModal'));

let ganttTasks = [];
let teams = [];  // Array di team {name, members: []}

let currentYear, currentMonth;
let daysInMonth;

// Mappa colori per team
let teamColors = {};

function fetchJSON(data){
  return fetch('api.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(data)
  }).then(r => r.json());
}

function getDaysInMonth(year, month){
  return new Date(year, month+1, 0).getDate();
}

function dayIndex(dateStr){
  let d = new Date(dateStr);
  return d.getDate() - 1;
}

function diffDays(startStr, endStr){
  const start = new Date(startStr);
  const end = new Date(endStr);
  return Math.floor((end - start)/(1000*60*60*24)) + 1;
}

function escapeHtml(text) {
  const map = {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'};
  return text.replace(/[&<>"']/g, m => map[m]);
}

// Genera un colore coerente a partire dal nome del team (hash semplice)
function colorFromName(name) {
  let hash = 0;
  for(let i = 0; i < name.length; i++){
    hash = name.charCodeAt(i) + ((hash << 5) - hash);
  }
  const c = (hash & 0x00FFFFFF).toString(16).toUpperCase();
  return '#' + '00000'.substring(0,6-c.length) + c;
}

// Controlla se data è sabato o domenica
function isWeekend(year, month, day){
  const dt = new Date(year, month, day);
  const wd = dt.getDay(); // giorno settimana 0=dom
  return (wd === 0 || wd === 6);
}

// Ritorna vero se il task intersecta il mese attuale
function taskInMonth(task, year, month){
  const start = new Date(task.start);
  const end = new Date(task.end);
  const startOfMonth = new Date(year, month, 1);
  const endOfMonth = new Date(year, month, getDaysInMonth(year, month));
  return (start <= endOfMonth && end >= startOfMonth);
}

// Rimuove tutte le barre task da DOM
function clearBars(){
  const bars = ganttWrapper.querySelectorAll('.task-bar');
  bars.forEach(b => b.remove());
}

// Costruisce griglia + barre
function renderGanttGrid(){
  daysInMonth = getDaysInMonth(currentYear, currentMonth);
  document.documentElement.style.setProperty('--days-in-month', daysInMonth);
  monthLabel.textContent = new Date(currentYear, currentMonth).toLocaleString('it-IT', {month:'long', year:'numeric'});

  // Crea griglia header e celle:

  let html = '';
  // Header row
  html += `<div class="gantt-header">`;
  html += `<div class="cell"></div>`; // spazio nome task

  for(let d = 1; d <= daysInMonth; d++) {
    const weekend = isWeekend(currentYear, currentMonth, d);
    html += `<div class="cell${weekend?' weekend':''}" role="columnheader" aria-label="Giorno ${d}">${d}</div>`;
  }
  html += `</div>`;

  // Righe task
  ganttTasks.forEach(task => {
    if(!taskInMonth(task, currentYear, currentMonth)) return; // Skip fuori mese

    html += `<div class="gantt-row" role="row">`;

    html += `<div class="task-name-cell" role="rowheader" tabindex="0" aria-label="Task: ${escapeHtml(task.title)}">${escapeHtml(task.title)}</div>`;

    for(let d=1; d<=daysInMonth; d++){
      const weekend = isWeekend(currentYear, currentMonth, d);
      html += `<div class="day-cell${weekend?' weekend':''}" role="gridcell"></div>`;
    }

    html += `</div>`;
  });

  ganttGrid.innerHTML = html;

  clearBars();

// Genera mappa colori per team attuali (evito duplicati)
teamColors = {};
teams.forEach(team=>{
  teamColors[team.name] = colorFromName(team.name);
});

// CORREZIONE: usa un contatore per task visibili
let visibleTaskIndex = 0; // <-- Aggiungi questo

ganttTasks.forEach((task, idx)=>{
  if(!taskInMonth(task, currentYear, currentMonth)) return;

  const startDate = new Date(task.start);
  const endDate = new Date(task.end);

    // Calcola posizione e dimensioni
    let startOffset = 0, lengthDays = 0;

    if(startDate.getFullYear()===currentYear && startDate.getMonth()===currentMonth){
      // Task inizia questo mese
      startOffset = startDate.getDate() - 1;
      lengthDays = diffDays(task.start, task.end);
    } else if (startDate < new Date(currentYear, currentMonth, 1)){
      // Task inizia prima di questo mese, barra parte da 0
      startOffset = 0;
      // durata = dal primo giorno mese a fine task o fine mese (minore)
      let taskEndDate = endDate;
      let monthLastDay = new Date(currentYear, currentMonth, daysInMonth);
      let diffEnd = Math.min(taskEndDate, monthLastDay);
      lengthDays = Math.floor((diffEnd - new Date(currentYear, currentMonth, 1))/(1000*60*60*24)) + 1;
    }

    // Limita la barra alla fine del mese
    if(startOffset + lengthDays > daysInMonth) {
      lengthDays = daysInMonth - startOffset;
    }

    // CORREZIONE POSIZIONAMENTO: usa dimensioni coerenti con CSS
    const topPx = 31 + visibleTaskIndex * 38 + 10;  // <-- Cambia qui
  const leftPx = 160 + startOffset * 40;
  const widthPx = lengthDays * 40 - 4;
  const barHeight = '32px'; // Forza altezza fissa   

    // Crea barra
    const bar = document.createElement('div');
    bar.className = 'task-bar';
    bar.style.top = `${topPx}px`;
    bar.style.left = `${leftPx}px`;
    bar.style.width = `${widthPx}px`;
    bar.style.height = barHeight;      
    bar.style.backgroundColor = teamColors[task.team] || '#007bff';
    bar.setAttribute('tabindex','0');
    bar.setAttribute('role','button');
    bar.setAttribute('aria-label',`Task: ${task.title} dal ${task.start} al ${task.end}, Team: ${task.team}`);

    bar.dataset.index = idx;

    bar.innerHTML = `<div>${escapeHtml(task.title)}</div><div class="team-label">${escapeHtml(task.team)}</div>`;

    ganttWrapper.appendChild(bar);

    // Eventi edit
    bar.addEventListener('click', () => openGanttEditModal(idx));
    bar.addEventListener('keypress', e => {if(e.key==='Enter') openGanttEditModal(idx);});
        
    visibleTaskIndex++; // <-- QUI     
  });
}

function openGanttEditModal(idx){
  const task = ganttTasks[idx];
  if(!task) return;
  document.getElementById('gantt-edit-index').value = idx;
  document.getElementById('gantt-edit-title').value = task.title;
  document.getElementById('gantt-edit-start').value = task.start;
  document.getElementById('gantt-edit-end').value = task.end;
  document.getElementById('gantt-edit-team').value = task.team;
  ganttEditModal.show();
}

async function loadTeamsAndTasks(){
  // carica teams e popola
  const teamsRes = await fetchJSON({action: 'getTeamsWithMembers'});
  if(teamsRes.success){
    teams = teamsRes.data;
    populateTeamSelectors();
  }
  // carica tasks
  const tasksRes = await fetchJSON({action: 'getGantt'});
  if(tasksRes.success){
    ganttTasks = tasksRes.data;
  }
  renderGanttGrid();
}

function populateTeamSelectors(){
  const selects = [document.getElementById('gantt-team'), document.getElementById('gantt-edit-team')];
  selects.forEach(sel=>{
    sel.innerHTML = '<option value="">Seleziona team</option>';
    teams.forEach(t=>{
      const opt = document.createElement('option');
      opt.value = t.name;
      opt.textContent = t.name;
      sel.appendChild(opt);
    });
  });
}

// Aggiunta task
document.getElementById('gantt-form').addEventListener('submit', async e=>{
  e.preventDefault();

  const title = document.getElementById('gantt-title').value.trim();
  const start = document.getElementById('gantt-start').value;
  const end = document.getElementById('gantt-end').value;
  const team = document.getElementById('gantt-team').value;

  if(!title || !start || !end || !team) return alert('Compila tutti i campi');
  if(end < start) return alert('La data di fine non può essere precedente alla data di inizio');

  const res = await fetchJSON({action:'addGantt', title, start, end, team});
  if(res.success){
    ganttTasks = res.data;
    renderGanttGrid();
    e.target.reset();
    ganttWrapper.scrollLeft = 0;
  } else alert(res.message || 'Errore aggiungendo task');
});

// Salvataggio modifica task
document.getElementById('gantt-edit-form').addEventListener('submit', async e=>{
  e.preventDefault();

  const idx = parseInt(document.getElementById('gantt-edit-index').value);
  const title = document.getElementById('gantt-edit-title').value.trim();
  const start = document.getElementById('gantt-edit-start').value;
  const end = document.getElementById('gantt-edit-end').value;
  const team = document.getElementById('gantt-edit-team').value;

  if(!title || !start || !end || !team) return alert('Compila tutti i campi');
  if(end < start) return alert('La data di fine non può essere precedente alla data di inizio');

  const res = await fetchJSON({action:'editGantt', index: idx, title, start, end, team});
  if(res.success){
    ganttTasks = res.data;
    renderGanttGrid();
    ganttEditModal.hide();
  } else alert(res.message || 'Errore modificando task');
});

// Bottoni mese precedente/successivo
prevMonthBtn.addEventListener('click', () => {
  currentMonth--;
  if(currentMonth < 0){
    currentMonth = 11;
    currentYear--;
  }
  renderGanttGrid();
});

nextMonthBtn.addEventListener('click', () => {
  currentMonth++;
  if(currentMonth > 11){
    currentMonth = 0;
    currentYear++;
  }
  renderGanttGrid();
});

// Funzione per controllare i permessi utente
function checkUserPermissions() {
    const currentUser = getCurrentUser();
    
    if (!currentUser) {
        // Se non c'è utente loggato, reindirizza al login
        window.location.href = 'index.html';
        return;
    }
    
    const adminTaskSection = document.getElementById('admin-task-section');
    const userRestrictionMessage = document.getElementById('user-restriction-message');
    
    if (currentUser.level === 'admin' || currentUser.level === 'manager') {
        // Mostra sezione admin
        adminTaskSection.style.display = 'block';
        userRestrictionMessage.style.display = 'none';
    } else {
        // Nascondi sezione admin e mostra messaggio (opzionale)
        adminTaskSection.style.display = 'none';
        userRestrictionMessage.style.display = 'block'; // Decommenta se vuoi mostrare il messaggio
    }
}

// Funzione helper per ottenere l'utente corrente
function getCurrentUser() {
    try {
        const userData = localStorage.getItem('currentUser');
        return userData ? JSON.parse(userData) : null;
    } catch (error) {
        console.error('Errore nel parsing dei dati utente:', error);
        return null;
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
        
        
// Init con data attuale
const now = new Date();
currentYear = now.getFullYear();
currentMonth = now.getMonth();
        
        
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
                        

loadTeamsAndTasks();
</script>

</body>
</html>