<?php
// Configurações de conexão
$host = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
$username = '';
$password = '';

// Conectar ao servidor IMAP
$inbox = imap_open($host, $username, $password) or die('Não foi possível conectar: ' . imap_last_error());

// Buscar todos os e-mails da pasta "Sent"
$emails = imap_search($inbox, 'ALL');

// Se houver e-mails, pegar o mais recente
if ($emails) {
    // Ordenar em ordem decrescente para pegar o mais recente
    rsort($emails);

    // Pegar o ID do último e-mail enviado
    $last_email_id = $emails[0];

    // Pegar o conteúdo do e-mail
    $overview = imap_fetch_overview($inbox, $last_email_id, 0);
    $message = imap_fetchbody($inbox, $last_email_id, 1);

    // Exibir o assunto e o corpo do e-mail
    echo 'Assunto: ' . $overview[0]->subject . "\n";
    echo 'Data: ' . $overview[0]->date . "\n";
    echo 'Corpo: ' . $message . "\n";
} else {
    echo 'Nenhum e-mail encontrado na pasta "Sent".';
}

// Fechar a conexão
imap_close($inbox);
?>