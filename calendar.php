<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario Mensile Condiviso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    body, html { 
      height: 100%; 
      margin: 0; 
      background: linear-gradient(135deg, #667eea 0%, #0dcaf0 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }          
          
    .calendar-container {
        max-width: 1200px;
        margin: 0 auto;
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

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .calendar-day-header {
        background-color: #495057;
        color: white;
        padding: 1rem;
        text-align: center;
        font-weight: bold;
    }
    
    .calendar-day {
        background-color: white;
        min-height: 120px;
        padding: 0.5rem;
        position: relative;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .calendar-day:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .calendar-day.other-month {
        background-color: #f8f9fa;
        color: #6c757d;
    }
    
    .calendar-day.today {
        background-color: #e3f2fd;
        border: 2px solid #2196f3;
    }
    
    .day-number {
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .calendar-event {
        background: linear-gradient(45deg, #ff6b6b, #ee5a52);
        color: white;
        padding: 0.2rem 0.4rem;
        margin: 0.1rem 0;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .calendar-event:hover {
        transform: scale(1.05);
    }
    
    .legend {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .legend-item {
        display: inline-flex;
        align-items: center;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        margin-right: 0.5rem;
    }
    
    .add-event-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
        z-index: 1000;
        display: none;
    }
    
    .add-event-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px 8px 0 0;
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
<body class="bg-light">
    <div class="container mt-3">
        <div class="calendar-container">
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
                    <h1><i class="fas fa-calendar-alt me-2"></i>Shared Calendar</h1>
                    <a href="dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <button class="btn btn-info btn-sm" onclick="previousMonth()">
                        <i class="fas fa-chevron-left me-2"></i>
                        Mese Precedente
                    </button>
                </div>
                <div class="col-md-4 text-center">
                    <h3 id="currentMonthYear" class="mb-0"></h3>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-info btn-sm" onclick="nextMonth()">
                        Mese Successivo
                        <i class="fas fa-chevron-right ms-2"></i>
                    </button>
                </div>
            </div>

            <div class="legend">
                <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Legenda Team</h6>
                <div id="legendContainer">
                    <!-- Dinamico -->
                </div>
            </div>

            <div class="calendar-grid" id="calendarGrid">
                <div class="calendar-day-header">Lun</div>
                <div class="calendar-day-header">Mar</div>
                <div class="calendar-day-header">Mer</div>
                <div class="calendar-day-header">Gio</div>
                <div class="calendar-day-header">Ven</div>
                <div class="calendar-day-header">Sab</div>
                <div class="calendar-day-header">Dom</div>
            </div>
        </div>
    </div>

    <button class="add-event-btn" id="add-event-btn" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fas fa-plus"></i>
    </button>

    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus me-2"></i>
                        Aggiungi Nuovo Evento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <input type="hidden" id="eventIndex" value="">
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Titolo Evento</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="eventStartDate" class="form-label">Data Inizio</label>
                                <input type="date" class="form-control" id="eventStartDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="eventEndDate" class="form-label">Data Fine</label>
                                <input type="date" class="form-control" id="eventEndDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="eventTeam" class="form-label">Team</label>
                            <select class="form-select" id="eventTeam" required>
                                <option value="">Seleziona Team</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="eventDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" onclick="saveEvent()">Salva Evento</button> 
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="eventDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dettagli Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventDetailsContent"></div>
                <div class="modal-footer" id="eventDetailsFooter">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <!-- Pulsanti edit/delete verranno aggiunti dinamicamente se manager+ -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="auth.js"></script>
    <script>
        let currentDate = new Date();
        let events = [];
        let teams = [];
        let selectedEventIndex = null;

        document.addEventListener('DOMContentLoaded', function() {
            updateDashboardUserInfo();
            if (isAdminOrManager()) {
                document.getElementById('add-event-btn').style.display = 'block';
            }
            loadTeams();
            loadEvents();
        });

        async function loadTeams() {
            const result = await fetchWithAuth({ action: 'getTeams' });
            if (result.success) {
                teams = result.data;
                populateTeamDropdown();
                renderLegend();
            }
        }

        function populateTeamDropdown() {
            const teamSelect = document.getElementById('eventTeam');
            teamSelect.innerHTML = '<option value="">Seleziona Team</option>';
            teams.forEach(team => {
                const option = document.createElement('option');
                option.value = team.name;
                option.textContent = team.name;
                teamSelect.appendChild(option);
            });
        }

        function colorFromName(name) {
            let hash = 0;
            for(let i = 0; i < name.length; i++){
              hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            const c = (hash & 0x00FFFFFF).toString(16).toUpperCase();
            return '#' + '00000'.substring(0,6-c.length) + c;
        }

        function renderLegend() {
            const container = document.getElementById('legendContainer');
            container.innerHTML = '';
            teams.forEach(team => {
                const item = document.createElement('div');
                item.className = 'legend-item';
                item.innerHTML = `
                    <div class="legend-color" style="background-color: ${colorFromName(team.name)}"></div>
                    <span>${team.name}</span>
                `;
                container.appendChild(item);
            });
        }

        async function loadEvents() {
            const result = await fetchWithAuth({ action: 'getCalendarEvents' });
            if (result.success) {
                events = result.data;
                renderCalendar();
            }
        }

        function renderCalendar() {
            const grid = document.getElementById('calendarGrid');
            const monthYear = document.getElementById('currentMonthYear');
            const monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
            monthYear.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            
            const days = grid.querySelectorAll('.calendar-day');
            days.forEach(day => day.remove());
            
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const daysInMonth = lastDay.getDate();
            
            let startingDayOfWeek = firstDay.getDay();
            startingDayOfWeek = startingDayOfWeek === 0 ? 6 : startingDayOfWeek - 1;
            
            const prevMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 0);
            const daysInPrevMonth = prevMonth.getDate();
            
            for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                grid.appendChild(createDayDiv(daysInPrevMonth - i, true));
            }
            
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = today.getDate() === day && today.getMonth() === currentDate.getMonth() && today.getFullYear() === currentDate.getFullYear();
                grid.appendChild(createDayDiv(day, false, isToday));
            }
            
            const totalCells = grid.children.length - 7;
            const remainingCells = 42 - totalCells;
            for (let day = 1; day <= remainingCells; day++) {
                grid.appendChild(createDayDiv(day, true));
            }
        }

        function createDayDiv(dayNumber, isOtherMonth = false, isToday = false) {
            const dayDiv = document.createElement('div');
            dayDiv.className = `calendar-day ${isOtherMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}`;
            
            const dayNumberDiv = document.createElement('div');
            dayNumberDiv.className = 'day-number';
            dayNumberDiv.textContent = dayNumber;
            dayDiv.appendChild(dayNumberDiv);
            
            if (!isOtherMonth) {
                const currentDateStr = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                const checkDate = new Date(currentDateStr);
                
                events.forEach((event, index) => {
                    const startDate = new Date(event.start);
                    const endDate = new Date(event.end);
                    if (checkDate >= startDate && checkDate <= endDate) {
                        const eventDiv = document.createElement('div');
                        eventDiv.className = 'calendar-event';
                        eventDiv.style.backgroundColor = colorFromName(event.team);
                        eventDiv.textContent = event.title;
                        eventDiv.onclick = () => showEventDetails(event, index);
                        dayDiv.appendChild(eventDiv);
                    }
                });
            }
            return dayDiv;
        }

        function showEventDetails(event, index) {
            selectedEventIndex = index;
            const content = document.getElementById('eventDetailsContent');
            content.innerHTML = `
                <div class="mb-3"><strong>Titolo:</strong><p>${event.title}</p></div>
                <div class="mb-3"><strong>Periodo:</strong><p>${formatDate(event.start)} - ${formatDate(event.end)}</p></div>
                <div class="mb-3"><strong>Team:</strong><p>${event.team}</p></div>
                ${event.description ? `<div class="mb-3"><strong>Descrizione:</strong><p>${event.description}</p></div>` : ''}
            `;
            
            const footer = document.getElementById('eventDetailsFooter');
            footer.innerHTML = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>';
            
            if (isAdminOrManager()) {
                const editBtn = document.createElement('button');
                editBtn.className = 'btn btn-warning';
                editBtn.innerHTML = '<i class="fas fa-edit me-2"></i>Modifica';
                editBtn.onclick = () => editEvent();
                footer.appendChild(editBtn);

                const deleteBtn = document.createElement('button');
                deleteBtn.className = 'btn btn-danger';
                deleteBtn.innerHTML = '<i class="fas fa-trash me-2"></i>Elimina';
                deleteBtn.onclick = () => deleteEvent();
                footer.appendChild(deleteBtn);
            }
            
            new bootstrap.Modal(document.getElementById('eventDetailsModal')).show();
        }

        function editEvent() {
            if (selectedEventIndex === null) return;
            const event = events[selectedEventIndex];
            document.getElementById('eventIndex').value = selectedEventIndex;
            document.getElementById('eventTitle').value = event.title;
            document.getElementById('eventStartDate').value = event.start;
            document.getElementById('eventEndDate').value = event.end;
            document.getElementById('eventTeam').value = event.team;
            document.getElementById('eventDescription').value = event.description || '';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Modifica Evento';
            bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
            setTimeout(() => { new bootstrap.Modal(document.getElementById('addEventModal')).show(); }, 300);
        }

        async function deleteEvent() {
            if (selectedEventIndex === null || !confirm('Sicuro di voler eliminare?')) return;
            const result = await fetchWithAuth({ action: 'deleteCalendarEvent', index: selectedEventIndex });
            if (result.success) {
                events = result.data;
                renderCalendar();
                bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
            } else alert(result.message);
        }

        async function saveEvent() {
            const form = document.getElementById('eventForm');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            const index = document.getElementById('eventIndex').value;
            const data = {
                action: index !== '' ? 'editCalendarEvent' : 'addCalendarEvent',
                index: index !== '' ? parseInt(index) : undefined,
                title: document.getElementById('eventTitle').value.trim(),
                start: document.getElementById('eventStartDate').value,
                end: document.getElementById('eventEndDate').value,
                team: document.getElementById('eventTeam').value,
                description: document.getElementById('eventDescription').value.trim()
            };
            const result = await fetchWithAuth(data);
            if (result.success) {
                events = result.data;
                renderCalendar();
                bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                form.reset();
                document.getElementById('eventIndex').value = '';
            } else alert(result.message);
        }

        function previousMonth() { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); }
        function nextMonth() { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); }
        function formatDate(dateStr) { return new Date(dateStr).toLocaleDateString('it-IT'); }

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
