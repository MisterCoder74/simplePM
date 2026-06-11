<?php
header('Content-Type: application/json');

define('KANBAN_FILE', __DIR__.'/data/kanban.json');
define('GANTT_FILE', __DIR__.'/data/gantt.json');
define('NOTES_FILE', __DIR__.'/data/notes.json');
define('TEAM_FILE', __DIR__.'/data/teams.json');
define('ISSUES_FILE', __DIR__.'/data/issues.json');
define('USERS_FILE', __DIR__.'/data/users.json');
define('SETUP_FILE', __DIR__.'/data/setup.json');
define('FILES_FILE', __DIR__.'/data/files.json');
define('QUOTA_FILE', __DIR__.'/data/quota.json');
define('UPLOAD_DIR', __DIR__.'/shareddocs/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB
define('MAX_QUOTA', 2 * 1024 * 1024 * 1024); // 2GB
define('CALENDAR_FILE', __DIR__.'/data/calendar.json');

function readFiles() {
    return readJson(FILES_FILE);
}

function writeFiles($data) {
    writeJson(FILES_FILE, $data);
}

function readQuota() {
    $quota = readJson(QUOTA_FILE);
    if (empty($quota)) {
        $quota = ['used' => 0, 'total' => MAX_QUOTA, 'limit' => MAX_QUOTA];
        writeJson(QUOTA_FILE, $quota);
    }
    return $quota;
}

function writeQuota($data) {
    writeJson(QUOTA_FILE, $data);
}

function getFileCategory($mimeType) {
    if (strpos($mimeType, 'image/') === 0) {
        return 'images';
    } elseif ($mimeType === 'application/pdf') {
        return 'pdfs';
    } else {
        return 'docs';
    }
}

function generateUniqueFilename($originalName, $uploadDir) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = pathinfo($originalName, PATHINFO_FILENAME);
    $filename = $basename . '.' . $extension;
    $counter = 1;
    
    while (file_exists($uploadDir . $filename)) {
        $filename = $basename . '_' . $counter . '.' . $extension;
        $counter++;
    }
    
    return $filename;
}

function updateQuotaUsage() {
    $files = readFiles();
    $totalSize = 0;
    
    foreach ($files as $file) {
        $totalSize += $file['size'];
    }
    
    $quota = readQuota();
    $quota['used'] = $totalSize;
    writeQuota($quota);
    
    return $quota;
}

// Funzioni per la gestione calendario
function readCalendar() {
    return readJson(CALENDAR_FILE);
}

function writeCalendar($data) {
    writeJson(CALENDAR_FILE, $data);
}

function generateEventId() {
    return uniqid('cal_event_');
}

function validateEventData($title, $start, $end, $team) {
    // Controlla che i campi obbligatori non siano vuoti
    if (empty(trim($title)) || empty($start) || empty($end) || empty($team)) {
        return 'Tutti i campi obbligatori devono essere compilati';
    }
    
    // Controlla formato date
    if (!validateDate($start) || !validateDate($end)) {
        return 'Formato date non valido';
    }
    
    // Controlla che la data di fine non sia precedente a quella di inizio
    if ($end < $start) {
        return 'La data di fine non può essere precedente a quella di inizio';
    }
    
    return null; // Nessun errore
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function validateDateTime($datetime) {
    $d = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
    return $d && $d->format('Y-m-d H:i:s') === $datetime;
}

function sendResponse($success, $message = '', $data = null) {
    // Imposta l'header per JSON
    header('Content-Type: application/json');
    
    // Prepara la risposta
    $response = array(
        'success' => $success,
        'message' => $message
    );
    
    // Aggiungi i dati solo se presenti
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    // Invia la risposta JSON e termina lo script
    echo json_encode($response);
    exit();
}

function loadData($filePath) {
    // Controlla se il file esiste
    if (!file_exists($filePath)) {
        // Se il file non esiste, restituisci un array vuoto
        return array();
    }
    
    // Leggi il contenuto del file
    $jsonContent = file_get_contents($filePath);
    
    // Se il file è vuoto, restituisci un array vuoto
    if (empty($jsonContent)) {
        return array();
    }
    
    // Decodifica il JSON
    $data = json_decode($jsonContent, true);
    
    // Se c'è un errore nella decodifica JSON, restituisci un array vuoto
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Errore nella decodifica JSON del file $filePath: " . json_last_error_msg());
        return array();
    }
    
    // Restituisci i dati decodificati
    return $data;
}

function saveData($filePath, $data) {
    // Controlla se la directory esiste, altrimenti creala
    $directory = dirname($filePath);
    if (!is_dir($directory)) {
        if (!mkdir($directory, 0755, true)) {
            error_log("Impossibile creare la directory: $directory");
            return false;
        }
    }
    
    // Converti i dati in JSON con formattazione leggibile
    $jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    // Controlla se la codifica JSON è riuscita
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Errore nella codifica JSON: " . json_last_error_msg());
        return false;
    }
    
    // Salva il file con backup temporaneo per sicurezza
    $tempFile = $filePath . '.tmp';
    
    // Scrivi nel file temporaneo
    if (file_put_contents($tempFile, $jsonContent, LOCK_EX) === false) {
        error_log("Impossibile scrivere nel file temporaneo: $tempFile");
        return false;
    }
    
    // Se tutto è andato bene, sostituisci il file originale
    if (!rename($tempFile, $filePath)) {
        error_log("Impossibile rinominare il file temporaneo: $tempFile -> $filePath");
        // Pulisci il file temporaneo in caso di errore
        unlink($tempFile);
        return false;
    }
    
    return true;
}

function handleGetCalendarEvents() {
    $ganttFile = GANTT_FILE;
    $events = loadData($ganttFile);
    
    // Aggiungi ID univoci se non presenti
    foreach ($events as $index => &$event) {
      if (!isset($event['id'])) {
            $event['id'] = 'task_' . $index;
        }
    }
    
    sendResponse(true, '', $events);
}

// Aggiungi evento calendario
function handleAddCalendarEvent($input) {
    $ganttFile = GANTT_FILE;
    
    $title = trim($input['title'] ?? '');
    $startDate = $input['start'] ?? '';
    $endDate = $input['end'] ?? '';
    $team = $input['team'] ?? '';
    
    if (empty($title) || empty($startDate) || empty($endDate) || empty($team)) {
        sendResponse(false, 'Tutti i campi obbligatori devono essere compilati');
    }
    
    // Validazione date
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    
    if ($end < $start) {
        sendResponse(false, 'La data di fine deve essere successiva alla data di inizio');
    }
    
    $events = loadData($ganttFile);
    
    $newEvent = [
        'title' => $title,
        'start' => $startDate,
        'end' => $endDate,
        'team' => $team
    ];
    
    $events[] = $newEvent;
    
    if (saveData($ganttFile, $events)) {
        sendResponse(true, 'Evento aggiunto con successo', $events);
    } else {
        sendResponse(false, 'Errore nell\'aggiunta dell\'evento');
    }
}

// Modifica evento calendario
function handleEditCalendarEvent($input) {
    $ganttFile = GANTT_FILE;
    
    $index = $input['index'] ?? -1;
    $title = trim($input['title'] ?? '');
    $startDate = $input['start'] ?? '';
    $endDate = $input['end'] ?? '';
    $team = $input['team'] ?? '';
    
    if ($index < 0 || empty($title) || empty($startDate) || empty($endDate) || empty($team)) {
        sendResponse(false, 'Dati non validi per la modifica');
    }
    
    // Validazione date
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    
    if ($end < $start) {
        sendResponse(false, 'La data di fine deve essere successiva alla data di inizio');
    }
    
    $events = loadData($ganttFile);
    
    if (!isset($events[$index])) {
        sendResponse(false, 'Evento non trovato');
    }
    
    // Aggiorna i dati dell'evento
    $events[$index]['title'] = $title;
    $events[$index]['start'] = $startDate;
    $events[$index]['end'] = $endDate;
    $events[$index]['team'] = $team;
    
    if (saveData($ganttFile, $events)) {
        sendResponse(true, 'Evento modificato con successo', $events);
    } else {
        sendResponse(false, 'Errore nella modifica dell\'evento');
    }
}

// Elimina evento calendario
function handleDeleteCalendarEvent($input) {
    $ganttFile = GANTT_FILE;
    
    $index = $input['index'] ?? -1;
    
    if ($index < 0) {
        sendResponse(false, 'Indice evento non valido');
    }
    
    $events = loadData($ganttFile);
    
    if (!isset($events[$index])) {
        sendResponse(false, 'Evento non trovato');
    }
    
    array_splice($events, $index, 1);
    
    if (saveData($ganttFile, $events)) {
        sendResponse(true, 'Evento eliminato con successo', $events);
    } else {
        sendResponse(false, 'Errore nell\'eliminazione dell\'evento');
    }
}

// Funzioni per la gestione utenti
function readUsers() {
    return readJson(USERS_FILE);
}

function writeUsers($data) {
    writeJson(USERS_FILE, $data);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidUserLevel($level) {
    return in_array($level, ['user', 'admin', 'manager']);
}

function readJson($file) {
if (!file_exists($file)) {
file_put_contents($file, json_encode([]));
}
$content = file_get_contents($file);
$data = json_decode($content, true);
if ($data === null) return [];
return $data;
}

function writeJson($file, $data) {
file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Funzioni specifiche per issues
function readIssues() {
return readJson(ISSUES_FILE);
}
function writeIssues($data) {
writeJson(ISSUES_FILE, $data);
}

// Lettura del file JSON e decodifica in array
$setupData = json_decode(file_get_contents('./data/setup.json'), true);

// Ottieni l'indirizzo email dal file JSON
$recipientEmail = isset($setupData['email']) ? $setupData['email'] : 'info@vivacitydesign.net';

// Modifica delle funzioni per usare questa variabile

//function sendIssueEscalationEmail($issue, $recipientEmail) {
function sendIssueEscalationEmail($issue) {
//echo '<script>alert(' . $recipientEmail . ');</script>';
$recipientEmail = isset($setupData['email']) ? $setupData['email'] : 'info@vivacitydesign.net';        
$to = $recipientEmail;
$subject = "Issue ID: " . $issue['id'] . " escalation";

$body = "L'issue con ID: " . $issue['id'] . " è stato escalato.\n\n";
$body .= "Dettagli issue:\n";
$body .= "Task: " . $issue['taskTitle'] . "\n";
$body .= "Description: " . $issue['description'] . "\n";
$body .= "Data creazione: " . $issue['created_at'] . "\n";
$body .= "Data escalation: " . $issue['escalated_at'] . "\n";
$body .= "Stato attuale: " . $issue['status'] . "\n";

$headers = "From: no-reply@vivacitydesign.net\r\n" .
"Reply-To: no-reply@vivacitydesign.net\r\n" .
"X-Mailer: PHP/" . phpversion();

mail($to, $subject, $body, $headers);
}

//function sendIssueCompleteEmail($issue, $recipientEmail) {
function sendIssueCompleteEmail($issue) {
$recipientEmail = isset($setupData['email']) ? $setupData['email'] : 'info@vivacitydesign.net';        
$to = $recipientEmail;
$subject = "Issue ID: " . $issue['id'] . " solved";

$body = "L'issue con ID: " . $issue['id'] . " è stato completato (risolto).\n\n";
$body .= "Dettagli issue:\n";
$body .= "Task: " . $issue['taskTitle'] . "\n";
$body .= "Description: " . $issue['description'] . "\n";
$body .= "Data creazione: " . $issue['created_at'] . "\n";
$body .= "Data escalation: " . $issue['escalated_at'] . "\n";
$body .= "Data completamento: " . $issue['resolved_at'] . "\n";
$body .= "Stato attuale: " . $issue['status'] . "\n";

$headers = "From: no-reply@vivacitydesign.net\r\n" .
"Reply-To: no-reply@vivacitydesign.net\r\n" .
"X-Mailer: PHP/" . phpversion();

mail($to, $subject, $body, $headers);
}

// Parsing richiesta - supporta sia JSON che form-data
$input = null;
$action = '';

// Se è una richiesta POST con form-data (upload file)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $input = $_POST;
    $action = $_POST['action'];

}

// Oppure, se ci sono parametri GET (come per il download)
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
$action = $_GET['action'];
}

// Se è una richiesta JSON normale
else {
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input && isset($input['action'])) {
        $action = $input['action'];
    }
}

// Controllo validità richiesta
if (!$action) {
    echo json_encode(['success'=>false, 'message'=>'Invalid request']);
    exit;
}

/* Parsing richiesta JSON
$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['action'])) {
echo json_encode(['success'=>false, 'message'=>'Invalid request']);
exit;
}

$action = $input['action']; */

switch ($action) {
    case 'getCalendarEvents':
        handleGetCalendarEvents();
        break;
    
    case 'addCalendarEvent':
        handleAddCalendarEvent($input);
        break;
    
    case 'editCalendarEvent':
        handleEditCalendarEvent($input);
        break;
    
    case 'deleteCalendarEvent':
        handleDeleteCalendarEvent($input);
        break;                
                
                
case 'getFiles':
    $files = readFiles();
    echo json_encode(['success'=>true, 'data'=>$files]);
    break;

case 'getQuota':
    $quota = updateQuotaUsage();
    echo json_encode(['success'=>true, 'data'=>$quota]);
    break;

case 'uploadFile':
    if (!isset($_FILES['file'])) {
        echo json_encode(['success'=>false, 'message'=>'No file uploaded']);
        break;
    }
    
    $file = $_FILES['file'];
    
    // Controllo errori upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success'=>false, 'message'=>'Upload error: ' . $file['error']]);
        break;
    }
    
    $originalName = $file['name'];
    $tempPath = $file['tmp_name'];
    $fileSize = $file['size'];
    $mimeType = $file['type'];
    
                // Validazioni
    if ($fileSize > MAX_FILE_SIZE) {
        echo json_encode(['success'=>false, 'message'=>'File too large']);
        break;
    }
    
    if ($fileSize === 0) {
        echo json_encode(['success'=>false, 'message'=>'Empty file']);
        break;
    }
    
    $quota = readQuota();
    if ($quota['used'] + $fileSize > $quota['limit']) {
        echo json_encode(['success'=>false, 'message'=>'Storage quota exceeded']);
        break;
    }
    
    $category = getFileCategory($mimeType);
    $categoryDir = UPLOAD_DIR . $category . '/';
    
    // Crea directory se non esiste
    if (!is_dir($categoryDir)) {
        mkdir($categoryDir, 0755, true);
    }
    
    $filename = generateUniqueFilename($originalName, $categoryDir);
    $targetPath = $categoryDir . $filename;
    
    if (!move_uploaded_file($tempPath, $targetPath)) {
        echo json_encode(['success'=>false, 'message'=>'Failed to move uploaded file']);
        break;
    }
    
    // Salva info file
    $files = readFiles();
    $files[] = [
        'filename' => $filename,
        'original_name' => $originalName,
        'size' => $fileSize,
        'type' => $mimeType,
        'category' => $category,
        'uploaded_at' => date('Y-m-d H:i:s')
    ];
    
    writeFiles($files);
    updateQuotaUsage();
    
    echo json_encode(['success'=>true, 'message'=>'File uploaded successfully']);
    break;
case 'downloadFile':
    // Per i download, i parametri arrivano via GET
    $filename = $_GET['filename'] ?? '';
    if ($filename === '') {
        http_response_code(400);
        echo json_encode(['success'=>false, 'message'=>'Filename required']);
        exit;
    }
    
    $files = readFiles();
    $fileInfo = null;
    
    foreach ($files as $file) {
        if ($file['filename'] === $filename) {
            $fileInfo = $file;
            break;
        }
    }
    
    if (!$fileInfo) {
        http_response_code(404);
        echo json_encode(['success'=>false, 'message'=>'File not found']);
        exit;
    }
    
    $filePath = UPLOAD_DIR . $fileInfo['category'] . '/' . $filename;
    
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo json_encode(['success'=>false, 'message'=>'File not found on disk']);
        exit;
    }
    
    // Headers per download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileInfo['original_name'] . '"');
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: no-cache, must-revalidate');
    
    readfile($filePath);
    exit;

case 'getTeams':
$teams = readJson(TEAM_FILE);
echo json_encode(['success'=>true, 'data'=>$teams]);
break;

case 'getKanban':
$kanban = readJson(KANBAN_FILE);
echo json_encode(['success'=>true, 'data'=>$kanban]);
break;

case 'addKanban':
$title = trim($input['title'] ?? '');
$status = $input['status'] ?? '';
$team = $input['team'] ?? '';
$validStatuses = ['todo', 'inprogress', 'done'];
if ($title === '' || !in_array($status, $validStatuses) || $team === '') {
echo json_encode(['success'=>false, 'message'=>'Invalid Kanban task data']);
break;
}
$kanban = readJson(KANBAN_FILE);
$kanban[] = ['title'=>$title, 'status'=>$status, 'team'=>$team];
writeJson(KANBAN_FILE, $kanban);
echo json_encode(['success'=>true, 'data'=>$kanban]);
break;

case 'editKanban':
$index = isset($input['index']) ? (int)$input['index'] : -1;
$title = trim($input['title'] ?? '');
$status = $input['status'] ?? '';
$team = $input['team'] ?? '';
$validStatuses = ['todo','inprogress','done'];
if ($index < 0 || $title === '' || !in_array($status, $validStatuses) || $team === '') {
echo json_encode(['success'=>false, 'message'=>'Invalid data for Kanban edit']);
break;
}
$kanban = readJson(KANBAN_FILE);
if (!isset($kanban[$index])) {
echo json_encode(['success'=>false, 'message'=>'Kanban task index not found']);
break;
}
$kanban[$index] = ['title'=>$title, 'status'=>$status, 'team'=>$team];
writeJson(KANBAN_FILE, $kanban);
echo json_encode(['success'=>true, 'data'=>$kanban]);
break;

case 'deleteKanban':
$index = isset($input['index']) ? (int)$input['index'] : -1;
$kanban = readJson(KANBAN_FILE);
if (!isset($kanban[$index])) {
echo json_encode(['success'=>false, 'message'=>'Invalid Kanban task index']);
break;
}
array_splice($kanban, $index, 1);
writeJson(KANBAN_FILE, $kanban);
echo json_encode(['success'=>true]);
break;

case 'getGantt':
$gantt = readJson(GANTT_FILE);
echo json_encode(['success'=>true, 'data'=>$gantt]);
break;

case 'addGantt':
$title = trim($input['title'] ?? '');
$start = $input['start'] ?? '';
$end = $input['end'] ?? '';
$team = $input['team'] ?? '';
if ($title === '' || $start === '' || $end === '' || $team === '') {
echo json_encode(['success'=>false, 'message'=>'Invalid Gantt task data']);
break;
}
if ($end < $start) {
echo json_encode(['success'=>false, 'message'=>'End date cannot be before start date']);
break;
}
$gantt = readJson(GANTT_FILE);
$gantt[] = ['title'=>$title, 'start'=>$start, 'end'=>$end, 'team'=>$team];
writeJson(GANTT_FILE, $gantt);
echo json_encode(['success'=>true, 'data'=>$gantt]);
break;

case 'editGantt':
$index = isset($input['index']) ? (int)$input['index'] : -1;
$title = trim($input['title'] ?? '');
$start = $input['start'] ?? '';
$end = $input['end'] ?? '';
$team = $input['team'] ?? '';
if ($index < 0 || $title === '' || $start === '' || $end === '' || $team === '') {
echo json_encode(['success'=>false, 'message'=>'Invalid data for Gantt edit']);
break;
}
if ($end < $start) {
echo json_encode(['success'=>false, 'message'=>'End date cannot be before start date']);
break;
}
$gantt = readJson(GANTT_FILE);
if (!isset($gantt[$index])) {
echo json_encode(['success'=>false, 'message'=>'Gantt task index not found']);
break;
}
$gantt[$index] = ['title'=>$title, 'start'=>$start, 'end'=>$end, 'team'=>$team];
writeJson(GANTT_FILE, $gantt);
echo json_encode(['success'=>true, 'data'=>$gantt]);
break;

case 'deleteGantt':
$index = isset($input['index']) ? (int)$input['index'] : -1;
$gantt = readJson(GANTT_FILE);
if (!isset($gantt[$index])) {
echo json_encode(['success'=>false, 'message'=>'Invalid Gantt task index']);
break;
}
array_splice($gantt, $index, 1);
writeJson(GANTT_FILE, $gantt);
echo json_encode(['success'=>true]);
break;

case 'getNotes':
$notes = readJson(NOTES_FILE);
echo json_encode(['success'=>true, 'data'=>$notes]);
break;
// Aggiungi questi case al switch statement esistente in api.php

case 'addNote':
    $text = trim($input['text'] ?? '');
    $team = trim($input['team'] ?? '');
    if ($text === '') {
        echo json_encode(['success'=>false, 'message'=>'All fields required']);
        break;
    }
    $notes = readJson(NOTES_FILE);
    $notes[] = [
        'text' => $text,
        'team' => $team,
        'created_at' => date('Y-m-d H:i:s')
    ];
    writeJson(NOTES_FILE, $notes);
    echo json_encode(['success'=>true, 'data'=>$notes]);
    break;

case 'editNote':
    $index = isset($input['index']) ? (int)$input['index'] : -1;
    $text = trim($input['text'] ?? '');
    $team = trim($input['team'] ?? '');
    if ($index < 0 || $text === '') {
        echo json_encode(['success'=>false, 'message'=>'Invalid data for note edit']);
        break;
    }
    $notes = readJson(NOTES_FILE);
    if (!isset($notes[$index])) {
        echo json_encode(['success'=>false, 'message'=>'Note index not found']);
        break;
    }
    $notes[$index]['text'] = $text;
    $notes[$index]['team'] = $team;
    $notes[$index]['updated_at'] = date('Y-m-d H:i:s');
    writeJson(NOTES_FILE, $notes);
    echo json_encode(['success'=>true, 'data'=>$notes]);
    break;

case 'deleteNote':
    $index = isset($input['index']) ? (int)$input['index'] : -1;
    $notes = readJson(NOTES_FILE);
    if (!isset($notes[$index])) {
        echo json_encode(['success'=>false, 'message'=>'Invalid note index']);
        break;
    }
    array_splice($notes, $index, 1);
    writeJson(NOTES_FILE, $notes);
    echo json_encode(['success'=>true]);
    break;
case 'getTeamsWithMembers':
$teams = readJson(TEAM_FILE);
echo json_encode(['success' => true, 'data' => $teams]);
break;

case 'addTeam':
$name = trim($input['name'] ?? '');
if ($name === '') {
echo json_encode(['success'=>false,'message'=>'Nome team richiesto']);
break;
}
$teams = readJson(TEAM_FILE);
foreach($teams as $t){
if(strtolower($t['name']) === strtolower($name)){
echo json_encode(['success'=>false,'message'=>'Team già esistente']);
break 2;
}
}
$teams[] = ['name' => $name, 'members' => []];
writeJson(TEAM_FILE, $teams);
echo json_encode(['success'=>true]);
break;

case 'deleteTeam':
$index = isset($input['index']) ? (int)$input['index'] : -1;
$teams = readJson(TEAM_FILE);
if (!isset($teams[$index])) {
echo json_encode(['success'=>false,'message'=>'Indice team non valido']);
break;
}
array_splice($teams, $index, 1);
writeJson(TEAM_FILE, $teams);
echo json_encode(['success'=>true]);
break;

case 'addTeamMember':
$teamName = trim($input['teamName'] ?? '');
$memberName = trim($input['memberName'] ?? '');
if ($teamName === '' || $memberName === '') {
echo json_encode(['success'=>false,'message'=>'Dati mancanti']);
break;
}
$teams = readJson(TEAM_FILE);
$found = false;
foreach($teams as &$team){
if(strtolower($team['name']) === strtolower($teamName)){
// controllo duplicato membro
foreach($team['members'] as $m){
if(strtolower($m) === strtolower($memberName)){
echo json_encode(['success'=>false,'message'=>'Membro già presente']);
return;
}
}
$team['members'][] = $memberName;
$found = true;
break;
}
}
if (!$found) {
echo json_encode(['success'=>false,'message'=>'Team non trovato']);
break;
}
writeJson(TEAM_FILE, $teams);
echo json_encode(['success'=>true]);
break;

case 'deleteTeamMember':
$teamName = trim($input['teamName'] ?? '');
$memberIndex = isset($input['memberIndex']) ? (int)$input['memberIndex'] : -1;
if ($teamName === '' || $memberIndex < 0) {
echo json_encode(['success'=>false,'message'=>'Dati invalidi']);
break;
}
$teams = readJson(TEAM_FILE);
$found = false;
foreach($teams as &$team){
if(strtolower($team['name']) === strtolower($teamName)){
if (!isset($team['members'][$memberIndex])) {
echo json_encode(['success'=>false,'message'=>'Indice membro non valido']);
return;
}
array_splice($team['members'], $memberIndex, 1);
$found = true;
break;
}
}
if (!$found) {
echo json_encode(['success'=>false,'message'=>'Team non trovato']);
break;
}
writeJson(TEAM_FILE, $teams);
echo json_encode(['success'=>true]);
break;

case 'getIssues':
$issues = readIssues();
echo json_encode(['success'=>true, 'data'=>$issues]);
break;

case 'addIssue':
$taskTitle = trim($input['taskTitle'] ?? '');
$description = trim($input['description'] ?? '');
// 'reportedBy' rimosso
if ($taskTitle === '' || $description === '') {
echo json_encode(['success'=>false, 'message'=>'Missing data']);
break;
}
$issues = readIssues();
$newId = uniqid('issue_');
$issues[] = [
'id' => $newId,
'taskTitle' => $taskTitle,
'description' => $description,
'status' => 'pending',
// date of creation
'created_at' => date('Y-m-d H:i:s'),
'escalated_at' => null,
'resolved_at' => null
];
writeIssues($issues);
echo json_encode(['success'=>true, 'data'=>$issues]);
break;

case 'updateIssueStatus':
$index = isset($input['index']) ? (int)$input['index'] : -1;
$status = trim($input['status'] ?? '');
if ($index < 0 || !in_array($status,['pending','solved','escalated'])) {
echo json_encode(['success'=>false, 'message'=>'Invalid data']);
break;
}
$issues = readIssues();
if (!isset($issues[$index])) {
echo json_encode(['success'=>false, 'message'=>'Issue not found']);
break;
}
// Se cambia a 'solved' o 'escalated', settiamo rispettivamente date di risoluzione/escalation
if ($status === 'solved') {
$issues[$index]['resolved_at'] = date('Y-m-d H:i:s');
$issues[$index]['status'] = $status;        
// Invia email di completamento
sendIssueCompleteEmail($issues[$index]);
}
if ($status === 'escalated') {
$issues[$index]['escalated_at'] = date('Y-m-d H:i:s');
$issues[$index]['status'] = $status;        
sendIssueEscalationEmail($issues[$index]);
}

writeIssues($issues);
echo json_encode(['success'=>true, 'data'=>$issues]);
break;

                
case 'register':
    $username = trim($input['username'] ?? '');
    $email = trim($input['email'] ?? '');
    $password = trim($input['password'] ?? '');
    $fullName = trim($input['fullName'] ?? '');
    
    // Validazioni
    if ($username === '' || $email === '' || $password === '' || $fullName === '') {
        echo json_encode(['success'=>false, 'message'=>'Tutti i campi sono obbligatori']);
        break;
    }
    
    if (!isValidEmail($email)) {
        echo json_encode(['success'=>false, 'message'=>'Email non valida']);
        break;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success'=>false, 'message'=>'Password deve essere di almeno 6 caratteri']);
        break;
    }
    
    $users = readUsers();
    
    // Controlla se username o email già esistono
    foreach($users as $user) {
        if (strtolower($user['username']) === strtolower($username)) {
            echo json_encode(['success'=>false, 'message'=>'Username già esistente']);
            break 2;
        }
        if (strtolower($user['email']) === strtolower($email)) {
            echo json_encode(['success'=>false, 'message'=>'Email già registrata']);
            break 2;
        }
    }
    
    // Crea nuovo utente con level di default 'user'
    $newUser = [
        'id' => uniqid('user_'),
        'username' => $username,
        'email' => $email,
        'fullName' => $fullName,
        'password' => $password,
        'created_at' => date('Y-m-d H:i:s'),
        'is_active' => true,
        'level' => 'user'  // Sempre 'user' per nuove registrazioni
    ];
    
    $users[] = $newUser;
    writeUsers($users);
    
    // Rimuovi password dalla risposta
    unset($newUser['password']);
    echo json_encode(['success'=>true, 'message'=>'Utente registrato con successo', 'user'=>$newUser]);
    break;

case 'login':
    $username = trim($input['username'] ?? '');
    $password = trim($input['password'] ?? '');
    
    if ($username === '' || $password === '') {
        echo json_encode(['success'=>false, 'message'=>'Username e password obbligatori']);
        break;
    }
    
    $users = readUsers();
    $foundUser = null;
    
    foreach($users as $user) {
        if ((strtolower($user['username']) === strtolower($username) || 
             strtolower($user['email']) === strtolower($username)) && 
            $user['is_active']) {
            $foundUser = $user;
            break;
        }
    }
    
    if (!$foundUser || $password != $foundUser['password']) {
        echo json_encode(['success'=>false, 'message'=>'Credenziali non valide']);
        break;
    }
    
    // Genera token di sessione (semplificato)
    $token = generateToken();
    
    // Rimuovi password dalla risposta
    unset($foundUser['password']);
    $foundUser['token'] = $token;
    
    echo json_encode(['success'=>true, 'message'=>'Login effettuato', 'user'=>$foundUser]);
    break;

case 'getUsers':
    $users = readUsers();
    // Rimuovi password da tutti gli utenti
    $safeUsers = array_map(function($user) {
        unset($user['password']);
        return $user;
    }, $users);
    
    echo json_encode(['success'=>true, 'data'=>$safeUsers]);
    break;

case 'getUsersForTeamAssignment':
    $users = readUsers();
    // Filtra solo utenti attivi e rimuovi password
    $activeUsers = array_filter($users, function($user) {
        return $user['is_active'];
    });
    
    $safeUsers = array_map(function($user) {
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'fullName' => $user['fullName'],
            'email' => $user['email']
        ];
    }, $activeUsers);
    
    echo json_encode(['success'=>true, 'data'=>array_values($safeUsers)]);
    break;

case 'updateUser':
    $userId = trim($input['userId'] ?? '');
    $fullName = trim($input['fullName'] ?? '');
    $email = trim($input['email'] ?? '');
    $isActive = isset($input['isActive']) ? (bool)$input['isActive'] : true;
    $level = trim($input['level'] ?? ''); // Nuovo parametro per aggiornare il level
    
    if ($userId === '' || $fullName === '' || $email === '') {
        echo json_encode(['success'=>false, 'message'=>'Dati mancanti']);
        break;
    }
    
    if (!isValidEmail($email)) {
        echo json_encode(['success'=>false, 'message'=>'Email non valida']);
        break;
    }
    
    // Validazione level se fornito
    if ($level !== '' && !in_array($level, ['user', 'admin', 'manager'])) {
        echo json_encode(['success'=>false, 'message'=>'Livello utente non valido']);
        break;
    }
    
    $users = readUsers();
    $userIndex = -1;
    
    // Trova l'utente
    foreach($users as $index => $user) {
        if ($user['id'] === $userId) {
            $userIndex = $index;
            break;
        }
    }
    
    if ($userIndex === -1) {
        echo json_encode(['success'=>false, 'message'=>'Utente non trovato']);
        break;
    }
    
    // Controlla se email è già usata da altro utente
    foreach($users as $index => $user) {
        if ($index !== $userIndex && strtolower($user['email']) === strtolower($email)) {
            echo json_encode(['success'=>false, 'message'=>'Email già in uso']);
            break 2;
        }
    }
    
    // Aggiorna utente
    $users[$userIndex]['fullName'] = $fullName;
    $users[$userIndex]['email'] = $email;
    $users[$userIndex]['is_active'] = $isActive;
    $users[$userIndex]['updated_at'] = date('Y-m-d H:i:s');
    
    // Aggiorna level solo se fornito
    if ($level !== '') {
        $users[$userIndex]['level'] = $level;
    }
    
    writeUsers($users);
    
    // Rimuovi password dalla risposta
    $updatedUser = $users[$userIndex];
    unset($updatedUser['password']);
    
    echo json_encode(['success'=>true, 'message'=>'Utente aggiornato', 'user'=>$updatedUser]);
    break;
                
case 'updateUserLevel':
    $userId = trim($input['userId'] ?? '');
    $level = trim($input['level'] ?? '');
    
    if ($userId === '' || $level === '') {
        echo json_encode(['success'=>false, 'message'=>'UserId e level sono obbligatori']);
        break;
    }
    
    // Validazione level
    if (!in_array($level, ['user', 'admin', 'manager'])) {
        echo json_encode(['success'=>false, 'message'=>'Livello utente non valido. Valori ammessi: user, admin, manager']);
        break;
    }
    
    $users = readUsers();
    $userIndex = -1;
    
    foreach($users as $index => $user) {
        if ($user['id'] === $userId) {
            $userIndex = $index;
            break;
        }
    }
    
    if ($userIndex === -1) {
        echo json_encode(['success'=>false, 'message'=>'Utente non trovato']);
        break;
    }
    
    $users[$userIndex]['level'] = $level;
    $users[$userIndex]['updated_at'] = date('Y-m-d H:i:s');
    
    writeUsers($users);
    
    // Rimuovi password dalla risposta
    $updatedUser = $users[$userIndex];
    unset($updatedUser['password']);
    
    echo json_encode(['success'=>true, 'message'=>'Livello utente aggiornato', 'user'=>$updatedUser]);
    break;
                

case 'changePassword':
    $userId = trim($input['userId'] ?? '');
    $currentPassword = trim($input['currentPassword'] ?? '');
    $newPassword = trim($input['newPassword'] ?? '');
    
    if ($userId === '' || $currentPassword === '' || $newPassword === '') {
        echo json_encode(['success'=>false, 'message'=>'Tutti i campi sono obbligatori']);
        break;
    }
    
    if (strlen($newPassword) < 6) {
        echo json_encode(['success'=>false, 'message'=>'Nuova password deve essere di almeno 6 caratteri']);
        break;
    }
    
    $users = readUsers();
    $userIndex = -1;
    
    foreach($users as $index => $user) {
        if ($user['id'] === $userId) {
            $userIndex = $index;
            break;
        }
    }
    
    if ($userIndex === -1) {
        echo json_encode(['success'=>false, 'message'=>'Utente non trovato']);
        break;
    }
    
    if (!verifyPassword($currentPassword, $users[$userIndex]['password'])) {
        echo json_encode(['success'=>false, 'message'=>'Password attuale non corretta']);
        break;
    }
    
    $users[$userIndex]['password'] = hashPassword($newPassword);
    $users[$userIndex]['updated_at'] = date('Y-m-d H:i:s');
    
    writeUsers($users);
    
    echo json_encode(['success'=>true, 'message'=>'Password cambiata con successo']);
    break;                
                
default:
echo json_encode(['success'=>false, 'message'=>'Unknown action']);
}
?>