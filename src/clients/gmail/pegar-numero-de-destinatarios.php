<?php
// Configurações de conexão
$hostname = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
$username = '';
$password = '';

$inbox = imap_open($hostname, $username, $password) or die('Erro ao conectar: ' . imap_last_error());

// Busca a última mensagem enviada
$emails = imap_search($inbox, 'ALL');

if ($emails) {
    // Ordena as mensagens por ordem decrescente (última mensagem primeiro)
    rsort($emails);

    // Obtém o último email
    $last_email_id = $emails[0];
    $overview = imap_fetch_overview($inbox, $last_email_id, 0);
    $message = imap_fetchbody($inbox, $last_email_id, 1);

    // Exibe detalhes do email
    echo "Assunto: " . $overview[0]->subject . "\n";
    echo "Data: " . $overview[0]->date . "\n";

    // Extrai os destinatários (To, CC, BCC)
    $header = imap_headerinfo($inbox, $last_email_id);
    
    // Contando o número de destinatários
    $recipients = array();

    // Destinatários principais
    if (isset($header->to)) {
        foreach ($header->to as $to) {
            $recipients[] = $to->mailbox . '@' . $to->host;
        }
    }

    // CC (com cópia)
    if (isset($header->cc)) {
        foreach ($header->cc as $cc) {
            $recipients[] = $cc->mailbox . '@' . $cc->host;
        }
    }

    // BCC (com cópia oculta)
    if (isset($header->bcc)) {
        foreach ($header->bcc as $bcc) {
            $recipients[] = $bcc->mailbox . '@' . $bcc->host;
        }
    }

    // Mostra os destinatários e conta
    echo "Destinatários: " . implode(', ', $recipients) . "\n";
    echo "Número total de destinatários: " . count($recipients) . "\n";
} else {
    echo "Nenhum e-mail encontrado.";
}

imap_close($inbox);
?>