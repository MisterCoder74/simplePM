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
        
        .calendar-event.team-I_Piendaisti {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
        }
        
        .calendar-event.team-SpidSupport {
            background: linear-gradient(45deg, #667eea, #764ba2);
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
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
        }
        
        .event-details {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        .nav-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            <!-- Header -->
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

            <!-- Navigazione Mese -->
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

            <!-- Legenda -->
            <div class="legend">
                <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Legenda Team</h6>
                <div id="legendContainer">
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(45deg, #4ecdc4, #44a08d);"></div>
                        <span>I_Piendaisti</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(45deg, #667eea, #764ba2);"></div>
                        <span>SpidSupport</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: linear-gradient(45deg, #ff6b6b, #ee5a52);"></div>
                        <span>Altri Team</span>
                    </div>
                </div>
            </div>

            <!-- Calendario -->
            <div class="calendar-grid" id="calendarGrid">
                <!-- Headers giorni della settimana -->
                <div class="calendar-day-header">Lun</div>
                <div class="calendar-day-header">Mar</div>
                <div class="calendar-day-header">Mer</div>
                <div class="calendar-day-header">Gio</div>
                <div class="calendar-day-header">Ven</div>
                <div class="calendar-day-header">Sab</div>
                <div class="calendar-day-header">Dom</div>
                <!-- I giorni verranno aggiunti dinamicamente -->
            </div>
        </div>
    </div>

    <!-- Pulsante Aggiungi Evento  - abilitare solo per admin se voluto 
    <button class="add-event-btn" data-bs-toggle="modal" data-bs-target="#addEventModal">
        <i class="fas fa-plus"></i>
    </button> -->

    <!-- Modal Aggiungi/Modifica Evento -->
     <!-- aggiunta evento da abilitare solo per admin se necessario -->      
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
                            <label for="eventTitle" class="form-label">
                                <i class="fas fa-heading me-2"></i>
                                Titolo Evento
                            </label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="eventStartDate" class="form-label">
                                    <i class="fas fa-calendar-day me-2"></i>
                                    Data Inizio
                                </label>
                                <input type="date" class="form-control" id="eventStartDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="eventEndDate" class="form-label">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Data Fine
                                </label>
                                <input type="date" class="form-control" id="eventEndDate" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="eventTeam" class="form-label">
                                <i class="fas fa-users me-2"></i>
                                Team
                            </label>
                            <select class="form-select" id="eventTeam" required>
                                <option value="">Seleziona Team</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">
                                <i class="fas fa-align-left me-2"></i>
                                Descrizione (Opzionale)
                            </label>
                            <textarea class="form-control" id="eventDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Annulla
                    </button>
                     
                    <button type="button" class="btn btn-primary" onclick="saveEvent()">
                        <i class="fas fa-save me-2"></i>
                        Salva Evento
                    </button> 
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Dettagli Evento -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Dettagli Evento
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventDetailsContent">
                    <!-- Contenuto dinamico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Chiudi
                    </button>
                    <!-- eliminazione e modifica evento da abilitare solo per admin se necessario -->     
                    <!-- <button type="button" class="btn btn-warning" onclick="editEvent()">
                        <i class="fas fa-edit me-2"></i>
                        Modifica
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteEvent()">
                        <i class="fas fa-trash me-2"></i>
                        Elimina
                    </button> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentDate = new Date();
        let events = [];
        let teams = [];
        let currentUser = null;
        let selectedEventIndex = null;

        // Inizializzazione
        document.addEventListener('DOMContentLoaded', function() {
            // Ottieni utente corrente dal localStorage
            const storedUser = localStorage.getItem('currentUser');
            if (storedUser) {
                currentUser = JSON.parse(storedUser);
                console.log('Utente corrente:', currentUser);
            } else {
                alert('Devi essere loggato per accedere al calendario');
                window.location.href = 'login.html';
                return;
            }

            loadTeams();
            loadEvents();
            renderCalendar();
        });

        // Carica team per il dropdown
        async function loadTeams() {
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ action: 'getTeams' })
                });
                
                const result = await response.json();
                if (result.success) {
                    teams = result.data;
                    populateTeamDropdown();
                }
            } catch (error) {
                console.error('Errore nel caricamento team:', error);
            }
        }

        // Popola il dropdown dei team
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

        // Carica eventi dal server
        async function loadEvents() {
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ action: 'getCalendarEvents' })
        });
        
        // Debug: controlla lo status della response
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Debug: ottieni il testo raw prima di fare il parsing JSON
        const textResponse = await response.text();
        console.log('Raw response:', textResponse);
        
        // Prova a fare il parsing JSON solo se la response sembra essere JSON
        if (textResponse.trim().startsWith('{') || textResponse.trim().startsWith('[')) {
            const result = JSON.parse(textResponse);
            
            if (result.success) {
                events = result.data;
                renderCalendar();
            }
        } else {
            console.error('La response non è JSON:', textResponse);
        }
        
    } catch (error) {
        console.error('Errore nel caricamento eventi:', error);
    }
}
        // Renderizza il calendario
        function renderCalendar() {
            const grid = document.getElementById('calendarGrid');
            const monthYear = document.getElementById('currentMonthYear');
            
            // Aggiorna titolo mese/anno
            const monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                              'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
            monthYear.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            
            // Rimuovi giorni esistenti (mantieni headers)
            const existingDays = grid.querySelectorAll('.calendar-day');
            existingDays.forEach(day => day.remove());
            
            // Calcola primo giorno del mese e numero di giorni
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const daysInMonth = lastDay.getDate();
            
            // Lunedì = 1, Domenica = 0, ma vogliamo Lunedì = 0
            let startingDayOfWeek = firstDay.getDay();
            startingDayOfWeek = startingDayOfWeek === 0 ? 6 : startingDayOfWeek - 1;
            
            // Aggiungi giorni del mese precedente
            const prevMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 0);
            const daysInPrevMonth = prevMonth.getDate();
            
            for (let i = startingDayOfWeek - 1; i >= 0; i--) {
                const dayDiv = createDayDiv(daysInPrevMonth - i, true);
                grid.appendChild(dayDiv);
            }
            
            // Aggiungi giorni del mese corrente
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = today.getDate() === day && 
                               today.getMonth() === currentDate.getMonth() && 
                               today.getFullYear() === currentDate.getFullYear();
                const dayDiv = createDayDiv(day, false, isToday);
                grid.appendChild(dayDiv);
            }
            
            // Aggiungi giorni del mese successivo per completare la griglia
            const totalCells = grid.children.length - 7; // -7 per gli headers
            const remainingCells = 42 - totalCells; // 6 settimane * 7 giorni = 42
            
            for (let day = 1; day <= remainingCells; day++) {
                const dayDiv = createDayDiv(day, true);
                grid.appendChild(dayDiv);
            }
        }

        // Crea un div per un giorno
        function createDayDiv(dayNumber, isOtherMonth = false, isToday = false) {
            const dayDiv = document.createElement('div');
            dayDiv.className = `calendar-day ${isOtherMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}`;
            
            const dayNumberDiv = document.createElement('div');
            dayNumberDiv.className = 'day-number';
            dayNumberDiv.textContent = dayNumber;
            dayDiv.appendChild(dayNumberDiv);
            
            if (!isOtherMonth) {
                // Aggiungi eventi per questo giorno
                const dayEvents = getEventsForDay(dayNumber);
                dayEvents.forEach(event => {
                    const eventDiv = document.createElement('div');
                    eventDiv.className = `calendar-event team-${event.team.replace(/\s+/g, '_')}`;
                    eventDiv.textContent = event.title;
                    eventDiv.onclick = () => showEventDetails(event);
                    dayDiv.appendChild(eventDiv);
                });
            }
            
            return dayDiv;
        }

        // Ottieni eventi per un giorno specifico
        function getEventsForDay(day) {
            const currentDateStr = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            
            return events.filter(event => {
                    console.log(event);
                const startDate = new Date(event.start);
                const endDate = new Date(event.end);
                const checkDate = new Date(currentDateStr);
                
                return checkDate >= startDate && checkDate <= endDate;
            });
        }

        // Mostra dettagli evento
        function showEventDetails(event) {
            selectedEventIndex = events.findIndex(e => e.id === event.id);
            
            const content = document.getElementById('eventDetailsContent');
            content.innerHTML = `
                <div class="mb-3">
                    <h6><i class="fas fa-heading me-2"></i>Titolo:</h6>
                    <p class="ms-3">${event.title}</p>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-calendar me-2"></i>Periodo:</h6>
                    <p class="ms-3">${formatDate(event.start)} - ${formatDate(event.end)}</p>
                </div>
                <div class="mb-3">
                    <h6><i class="fas fa-users me-2"></i>Team:</h6>
                    <p class="ms-3">${event.team}</p>
                </div>
                ${event.description ? `
                <div class="mb-3">
                    <h6><i class="fas fa-align-left me-2"></i>Descrizione:</h6>
                    <p class="ms-3">${event.description}</p>
                </div>
                ` : ''}
                <div class="mb-3">
                    <h6><i class="fas fa-user me-2"></i>Creato da:</h6>
                    <p class="ms-3">${event.created_by || 'N/A'}</p>
                </div>
                
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
            modal.show();
        }

        // Modifica evento
        function editEvent() {
            if (selectedEventIndex === null) return;
            
            const event = events[selectedEventIndex];
            
            // Popola il form con i dati dell'evento
            document.getElementById('eventIndex').value = selectedEventIndex;
            document.getElementById('eventTitle').value = event.title;
            document.getElementById('eventStartDate').value = event.start;
            document.getElementById('eventEndDate').value = event.end;
            document.getElementById('eventTeam').value = event.team;
            document.getElementById('eventDescription').value = event.description || '';
            
            // Cambia il titolo del modal
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Modifica Evento';
            
            // Chiudi il modal dei dettagli e apri quello di modifica
            bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
            
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('addEventModal'));
                modal.show();
            }, 300);
        }

        // Elimina evento
        async function deleteEvent() {
            if (selectedEventIndex === null) return;
            
            if (!confirm('Sei sicuro di voler eliminare questo evento?')) return;
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'deleteCalendarEvent',
                        index: selectedEventIndex
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    events = result.data;
                    renderCalendar();
                    bootstrap.Modal.getInstance(document.getElementById('eventDetailsModal')).hide();
                    selectedEventIndex = null;
                } else {
                    alert('Errore nell\'eliminazione: ' + result.message);
                }
            } catch (error) {
                console.error('Errore nell\'eliminazione evento:', error);
                alert('Errore nell\'eliminazione evento');
            }
        }

        // Salva evento (nuovo o modificato)
        async function saveEvent() {
            const form = document.getElementById('eventForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const eventIndex = document.getElementById('eventIndex').value;
            const isEdit = eventIndex !== '';
            
            const eventData = {
                action: isEdit ? 'editCalendarEvent' : 'addCalendarEvent',
                title: document.getElementById('eventTitle').value.trim(),
                start: document.getElementById('eventStartDate').value,
                end: document.getElementById('eventEndDate').value,
                team: document.getElementById('eventTeam').value,
                description: document.getElementById('eventDescription').value.trim()
            };
            
            if (isEdit) {
                eventData.index = parseInt(eventIndex);
            }
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(eventData)
                });
                
                const result = await response.json();
                if (result.success) {
                    events = result.data;
                    renderCalendar();
                    bootstrap.Modal.getInstance(document.getElementById('addEventModal')).hide();
                    resetForm();
                } else {
                    alert('Errore nel salvataggio: ' + result.message);
                }
            } catch (error) {
                console.error('Errore nel salvataggio evento:', error);
                alert('Errore nel salvataggio evento');
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('eventForm').reset();
            document.getElementById('eventIndex').value = '';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus me-2"></i>Aggiungi Nuovo Evento';
        }

        // Navigazione mesi
        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        // Utility functions
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('it-IT');
        }

        function formatDateTime(dateTimeStr) {
            const date = new Date(dateTimeStr);
            return date.toLocaleString('it-IT');
        }

        // Reset form quando si apre il modal per aggiungere
        document.getElementById('addEventModal').addEventListener('show.bs.modal', function() {
            if (document.getElementById('eventIndex').value === '') {
                resetForm();
            }
        });
            
       
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
                
                

            
    </script>
</body>
</html>