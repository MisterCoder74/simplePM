<?php 
$created_at = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Kanban Board</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
<style>
    body, html { 
      height: 100%; 
      margin: 0; 
      background: linear-gradient(135deg, #667eea 0%, #0d6efd 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .main-header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      margin-bottom: 20px;
      padding: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    
    .kanban-container {
      display: flex; 
      height: 65vh; 
      gap: 20px;
      padding: 10px;
      margin-bottom: 30px;
    }
    
    .kanban-column {
      flex: 1;
      border: none;
      border-radius: 15px;
      padding: 20px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      overflow-y: auto;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }
    
    .kanban-column:hover {
      transform: translateY(-2px);
    }
    
    .kanban-column h4, .kanban-column h5 {
      text-align: center;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 0.9rem;
    }
    
    .kanban-column:nth-child(1) h4, .kanban-column:nth-child(1) h5 {
      background: linear-gradient(45deg, #ff6b6b, #ffa500);
      color: white;
    }
    
    .kanban-column:nth-child(2) h4, .kanban-column:nth-child(2) h5 {
      background: linear-gradient(45deg, #4ecdc4, #44a08d);
      color: white;
    }
    
    .kanban-column:nth-child(3) h4, .kanban-column:nth-child(3) h5 {
      background: linear-gradient(45deg, #45b7d1, #96c93d);
      color: white;
    }
    
    .task-card {
      background: white;
      border: none;
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 15px;
      position: relative;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      /*cursor: move;*/
      border-left: 4px solid transparent;
    }
    
    .task-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .task-card[data-status="todo"] {
      border-left-color: #ff6b6b;
    }
    
    .task-card[data-status="inprogress"] {
      border-left-color: #4ecdc4;
    }
    
    .task-card[data-status="done"] {
      border-left-color: #45b7d1;
    }
    
    .btn-task {
      position: absolute;
      top: 10px;
      right: 10px;
      display: flex;
      gap: 5px;
      z-index: 10;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .task-card:hover .btn-task {
      opacity: 1;
    }
    
    .btn-task .btn {
      padding: 4px 8px;
      font-size: 0.7rem;
      border-radius: 6px;
    }
    
    .add-task-section {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    .add-task-section h4 {
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
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-outline-secondary {
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .modal-header {
      background: linear-gradient(45deg, #667eea, #764ba2);
      color: white;
      border-radius: 15px 15px 0 0;
      border-bottom: none;
    }
    
    .modal-title {
      font-weight: 600;
    }
    
    .btn-close {
      filter: brightness(0) invert(1);
    }
    
    .task-team-badge {
      background: linear-gradient(45deg, #667eea, #764ba2);
      color: white;
      padding: 4px 8px;
      border-radius: 15px;
      font-size: 0.7rem;
      font-weight: 500;
      margin-top: 8px;
      display: inline-block;
    }
    
    .task-priority {
      position: absolute;
      top: -5px;
      left: -5px;
      width: 15px;
      height: 15px;
      border-radius: 50%;
      background: #ff6b6b;
    }
    
    /* Scrollbar personalizzata */
    .kanban-column::-webkit-scrollbar {
      width: 6px;
    }
    
    .kanban-column::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }
    
    .kanban-column::-webkit-scrollbar-thumb {
      background: linear-gradient(45deg, #667eea, #764ba2);
      border-radius: 10px;
    }
    
    /* Animazioni */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .task-card {
      animation: slideIn 0.5s ease;
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
      .kanban-container {
        flex-direction: column;
        height: auto;
        gap: 15px;
      }
      
      .kanban-column {
        min-height: 200px;
      }
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
          Kanban Board
        </h1>
        <p class="text-muted mb-0">Organizza i tuoi task e monitora il progresso</p>
      </div>
      <a href="dashboard.php" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Back to Dashboard
      </a>
    </div>
  </div>
  
  <div class="kanban-container" id="kanban-board">
    <!-- Le colonne verranno generate dinamicamente dal JavaScript -->
    <div class="kanban-column">
      <h5><i class="fas fa-clock me-2"></i>To Do</h5>
    </div>
    <div class="kanban-column">
      <h5><i class="fas fa-cog me-2"></i>In Progress</h5>
    </div>
    <div class="kanban-column">
      <h5><i class="fas fa-check-circle me-2"></i>Done</h5>
    </div>
  </div>
  
  <div class="add-task-section">
    <h4>
      <i class="fas fa-plus-circle text-primary"></i>
      Add New Task
    </h4>
    <form id="kanban-form" class="row g-3">
      <div class="col-md-5">
        <div class="form-floating">
          <input type="text" id="kanban-title" class="form-control" placeholder="Task Title" required />
          <label for="kanban-title">
            <i class="fas fa-tasks me-2"></i>Task Title
          </label>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-floating">
          <select id="kanban-status" class="form-select" required>
            <option value="">Select Status...</option>
            <option value="todo">📋 To Do</option>
            <option value="inprogress">⚙️ In Progress</option>
            <option value="done">✅ Done</option>
          </select>
          <label for="kanban-status">
            <i class="fas fa-list me-2"></i>Status
          </label>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-floating">      
        <button class="btn btn-primary" type="submit">
          <i class="fas fa-save me-2"></i>Add Task
        </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="kanbanEditModal" tabindex="-1" aria-labelledby="kanbanEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="kanban-edit-form">
      <div class="modal-header">
        <h5 class="modal-title" id="kanbanEditModalLabel">
          <i class="fas fa-edit me-2"></i>
          Edit Kanban Task
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="kanban-edit-index" />
        <div class="mb-3">
          <label for="kanban-edit-title" class="form-label">
            <i class="fas fa-tasks me-2"></i>Title
          </label>
          <input id="kanban-edit-title" type="text" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="kanban-edit-status" class="form-label">
            <i class="fas fa-list me-2"></i>Status
          </label>
          <select id="kanban-edit-status" class="form-select" required>
            <option value="todo">📋 To Do</option>
            <option value="inprogress">⚙️ In Progress</option>
            <option value="done">✅ Done</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">
          <i class="fas fa-times me-2"></i>Cancel
        </button>
        <button class="btn btn-primary" type="submit">
          <i class="fas fa-save me-2"></i>Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="auth.js"></script>
<script>
  let kanbanTasks = [];

  const kanbanEditModal = new bootstrap.Modal(document.getElementById('kanbanEditModal'));

  async function loadData() {
    const kanbanRes = await fetchWithAuth({action:'getKanban'});
    if(kanbanRes.success){
      kanbanTasks = kanbanRes.data;
      renderKanban(kanbanTasks);
    }
  }

  function escapeHtml(text) {
    let map = {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'};
    return text.replace(/[&<>"']/g, m => map[m]);
  }

  function renderKanban(tasks){
    const board = document.getElementById('kanban-board');
    board.innerHTML = '';
    const statuses = ['todo','inprogress','done'];
    const titles = {'todo':'To Do','inprogress':'In Progress','done':'Done'};
    const currentUser = getCurrentUser();

    statuses.forEach(status=>{
      const col = document.createElement('div');
      col.className = 'kanban-column';
      col.innerHTML = `<h5>${titles[status]}</h5>`;

      tasks.forEach((task,i)=>{
        if(task.status === status){
          const card = document.createElement('div');
          card.className = 'task-card';
          
          let actionButtons = '';
          // Show edit/delete only if admin/manager or if it's user's own task
          if (isAdminOrManager() || (currentUser && task.team === currentUser.username)) {
            actionButtons = `
              <div class="btn-task">
                <button class="btn btn-sm btn-outline-primary btn-kanban-edit" data-index="${i}" title="Edit">&#9998;</button>
                <button class="btn btn-sm btn-outline-danger btn-kanban-delete" data-index="${i}" title="Delete">&times;</button>
              </div>
            `;
          }

          card.innerHTML = `
            <strong>${escapeHtml(task.title)}</strong><br/>
            <small>Author: ${escapeHtml(task.team)}</small>
            ${actionButtons}
          `;
          col.appendChild(card);
        }
      });
      board.appendChild(col);
    });

    document.querySelectorAll('.btn-kanban-edit').forEach(btn=>{
      btn.onclick = e => {
        const idx = e.currentTarget.dataset.index;
        openKanbanEditModal(idx);
      }
    });

    document.querySelectorAll('.btn-kanban-delete').forEach(btn=>{
      btn.onclick = async e => {
        if(!confirm('Delete this Kanban task?')) return;
        const idx = e.currentTarget.dataset.index;
        const res = await fetchWithAuth({action: 'deleteKanban', index: parseInt(idx)});
        if(res.success){
          kanbanTasks.splice(idx,1);
          renderKanban(kanbanTasks);
        } else alert(res.message || "Error deleting Kanban task");
      }
    });
  }

  function openKanbanEditModal(index){
    const task = kanbanTasks[index];
    if(!task) return;
    document.getElementById('kanban-edit-index').value = index;
    document.getElementById('kanban-edit-title').value = task.title;
    document.getElementById('kanban-edit-status').value = task.status;
    kanbanEditModal.show();
  }

document.getElementById('kanban-edit-form').addEventListener('submit', async e => {
    e.preventDefault();
    const index = parseInt(document.getElementById('kanban-edit-index').value);
    const title = document.getElementById('kanban-edit-title').value.trim();
    const status = document.getElementById('kanban-edit-status').value;
    
    const currentUser = getCurrentUser();
    if (!currentUser) return;
    
    // Maintain the original author or set to current user if missing
    const team = kanbanTasks[index].team || currentUser.username;
    
    if (!title || !status) {
        return alert('Tutti i campi sono obbligatori.');
    }
    
    const res = await fetchWithAuth({action:'editKanban', index, title, status, team});
    if(res.success){
        kanbanTasks = res.data;
        renderKanban(kanbanTasks);
        kanbanEditModal.hide();
    } else alert(res.message || 'Errore durante l\'aggiornamento del task');
});

document.getElementById('kanban-form').addEventListener('submit', async e => {
    e.preventDefault();
    const title = document.getElementById('kanban-title').value.trim();
    const status = document.getElementById('kanban-status').value;
    
    const currentUser = getCurrentUser();
    if (!currentUser) return;
    
    const team = currentUser.username;
    
    const res = await fetchWithAuth({action:'addKanban', title, status, team});
    if(res.success){
        kanbanTasks = res.data;
        renderKanban(kanbanTasks);
        e.target.reset();
    } else {
        alert(res.message || 'Errore durante l\'aggiunta del task');
    }
});

function updateDashboardUserInfo() {
    const currentUser = getCurrentUser();
    const userInfoDiv = document.getElementById('user-info');
    const userNameSpan = document.getElementById('user-name');
    const userLevelBadge = document.getElementById('user-level-badge');
    const userAvatar = document.getElementById('user-avatar');
    
    if (currentUser) {
        userInfoDiv.style.display = 'flex';
        userNameSpan.textContent = currentUser.fullName || currentUser.username;
        
        userLevelBadge.textContent = currentUser.level.toUpperCase();
        userLevelBadge.className = 'badge ' + getLevelBadgeClass(currentUser.level);
        
        userAvatar.textContent = getInitials(currentUser.fullName || currentUser.username);
        userAvatar.style.backgroundColor = getAvatarColor(currentUser.level);
    } else {
        window.location.href = 'index.html';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateDashboardUserInfo();
    loadData();
});
</script>
</body>
</html>
