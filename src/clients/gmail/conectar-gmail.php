<?php
// Configurações
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = '';
$password = ''; // Se estiver usando uma senha de aplicativo, coloque-a aqui

// Conectar ao servidor IMAP
$inbox = imap_open($hostname, $username, $password);

if (!$inbox) {
    echo 'Não foi possível conectar ao servidor IMAP: ' . imap_last_error();
    exit;
}

// Buscar e-mails
$emails = imap_search($inbox, 'ALL');

if ($emails) {
    foreach ($emails as $email_number) {
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        $message = imap_fetchbody($inbox, $email_number, 2); // Corpo da mensagem

        echo '<h2>Assunto: ' . htmlspecialchars($overview[0]->subject) . '</h2>';
        echo '<p>De: ' . htmlspecialchars($overview[0]->from) . '</p>';
        echo '<p>Mensagem: ' . nl2br(htmlspecialchars($message)) . '</p>';
    }
} else {
    echo 'Nenhum e-mail encontrado.';
}

// Fechar conexão
imap_close($inbox);
?>