simplePM
Simple Project Management — applicazione web per la gestione di progetti, task, team e documenti.

Caratteristiche Principali
Kanban Board (task con colonne todo/inprogress/done)
Gantt Chart (timeline mensile con barre colorate per team)
Calendario Condiviso (eventi team su griglia mensile)
Issue Tracker (segnalazione e risoluzione problemi con notifiche email)
File Sharing (upload/download con categorie e quota 2GB)
Sticky Notes (note collaborative)
Team Management (creazione team e gestione membri)
Tech Stack
Backend: Vanilla PHP (nessun framework)
Frontend: HTML, CSS, JavaScript con Bootstrap 5 e Font Awesome
Storage: File JSON (nessun database)
RBAC — Controllo Accessi a 3 Livelli
🟦 user (contributor)
Aggiunge task Kanban e sposta i propri task
Crea note personali (modifica/elimina solo le proprie)
Visualizza calendario, Gantt e documenti
Segnala issue (sola lettura)
🟨 manager (coordinatore)
Tutto ciò che fa user, più:
Crea/modifica/elimina task Gantt
Crea team e gestisce i membri
Uploada file
Escala e risolve issue
Modifica/elimina qualsiasi task Kanban e nota
Aggiunge eventi al calendario
🔴 admin (governante)
Tutto ciò che fa manager, più:
Promuove/retrocede utenti (user ↔ manager ↔ admin)
Disattiva/riattiva account
Elimina definitivamente utenti
Elimina team interi
Elimina qualsiasi file caricato
Elimina issue
Accede alla configurazione sistema (setup.json)
Struttura del Progetto
simplePM/
├── index.html # Login/Registrazione
├── dashboard.php # Dashboard principale (hub)
├── api.php # API REST monolitica
├── auth.js # Funzioni condivise autenticazione/RBAC
├── kanban.php # Kanban Board
├── gantt.php # Diagramma di Gantt
├── gantt_manuale.html # Manuale operativo Gantt
├── calendar.php # Calendario condiviso
├── notes.php # Sticky Notes
├── issues.php # Issue Tracker
├── team.php # Gestione Team + Utenti
├── documents.php # File Sharing
├── logout.php # Logout
├── README.md
├── data/ # Storage JSON
│ ├── users.json # Utenti
│ ├── teams.json # Team e membri
│ ├── kanban.json # Task Kanban
│ ├── gantt.json # Task Gantt
│ ├── notes.json # Note
│ ├── issues.json # Issue
│ ├── files.json # Metadata file
│ ├── quota.json # Quota storage
│ └── setup.json # Configurazione agenzia
└── shareddocs/ # File caricati
├── images/
├── pdfs/
└── docs/

API
Endpoint unico: api.php (metodo POST/GET con parametro "action")
Oltre 50 azioni per CRUD completo su tutte le entità.

Credenziali di Test
Email: mario.rossi@hotmail.it / Password: 30011978 (admin)
Nuovi utenti si registrano con livello "user"
Note
UI in italiano
Notifiche email per escalation/risoluzione issue
Quota storage: 2GB, limite file: 50MB
Sessione gestita via localStorage + token
