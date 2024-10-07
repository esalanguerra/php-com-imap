<?php
// Configurações de conexão para a pasta de e-mails enviados
$host_sent = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail';
$username = '';
$password = '';

// Configurações de conexão para a caixa de entrada
$host_inbox = '{imap.gmail.com:993/imap/ssl}INBOX';

// Conectar ao servidor IMAP (pasta de e-mails enviados)
$sent_mail = imap_open($host_sent, $username, $password) or die('Não foi possível conectar à pasta de enviados: ' . imap_last_error());

// Buscar todos os e-mails da pasta "Sent"
$emails = imap_search($sent_mail, 'ALL');

// Se houver e-mails, pegar o mais recente
if ($emails) {
    // Ordenar em ordem decrescente para pegar o mais recente
    rsort($emails);

    // Pegar o ID do último e-mail enviado
    $last_email_id = $emails[0];

    // Pegar o conteúdo do e-mail
    $overview = imap_fetch_overview($sent_mail, $last_email_id, 0);
    $message = imap_fetchbody($sent_mail, $last_email_id, 1);

    // Exibir o assunto e o corpo do e-mail enviado
    echo 'Assunto: ' . $overview[0]->subject . "\n";
    echo 'Data: ' . $overview[0]->date . "\n";
    echo 'Corpo: ' . $message . "\n";

    // Fechar a conexão com a pasta de enviados
    imap_close($sent_mail);

    // Agora, verificar se houve uma notificação de leitura na caixa de entrada
    $inbox = imap_open($host_inbox, $username, $password) or die('Não foi possível conectar à caixa de entrada: ' . imap_last_error());

    // Buscar e-mails com o termo "Read:" no assunto, indicando uma possível confirmação de leitura
    $read_confirmations = imap_search($inbox, 'SUBJECT "Read:"');

    if ($read_confirmations) {
        // Verificar se algum e-mail de confirmação de leitura corresponde ao último e-mail enviado
        foreach ($read_confirmations as $email_id) {
            $confirmation_overview = imap_fetch_overview($inbox, $email_id, 0);
            $confirmation_message = imap_fetchbody($inbox, $email_id, 1);

            echo "\n--- Confirmação de leitura encontrada ---\n";
            echo 'Assunto: ' . $confirmation_overview[0]->subject . "\n";
            echo 'Data: ' . $confirmation_overview[0]->date . "\n";
            echo 'Corpo: ' . $confirmation_message . "\n";
        }
    } else {
        echo "\nNenhuma confirmação de leitura foi encontrada.\n";
    }

    // Fechar a conexão com a caixa de entrada
    imap_close($inbox);
} else {
    echo 'Nenhum e-mail encontrado na pasta "Sent".';
}
?>