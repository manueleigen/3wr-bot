<?php

/**
 * Freigabe-Workflow: /freigabe, /private, Admin /allow*, /decline*, /ban
 * Nutzt tellogs/_privateRequest.log und _privateApproved.log
 */

$cmd = $ctx['command_arr'][0] ?? '';

if ($cmd === '/private' || $cmd === '/freigabe') {
    $config = $ctx['config'];
    $fileRequest = logPath($config, '_privateRequest.log');
    $writeMode = 'w';

    $msg = "' wants to become private\n" . $ctx['chat_name_normalized'] . ' (ID: ' . $ctx['person_id'] . ')';

    if ($ctx['chat_is_group']) {
        $msg .= "\n\n/allowGroup\n\n/declineGroup";
    } else {
        $msg .= "\n\n/allowPerson\n\n/declinePerson";
    }

    $msg = "'" . $ctx['chat_name'] . $msg . "\n\n" . $ctx['person_name'] . ' (ID: ' . $ctx['person_id'] . ')';

    $fileApproved = logPath($config, '_privateApproved.log');
    $userApproved = isApprovedForChat($ctx);

    if (
        !$userApproved
        || $ctx['chat_id'] === $config['chat_id_admin']
    ) {
        writeFileHandler($fileRequest, $ctx['chat_name_normalized'] . ':' . $ctx['chat_id'], $writeMode);
        sendMessage($ctx['bot_id'], $ctx['chat_id'], 'Moment, ich frage mal nach...', false);
        sendMessage($ctx['bot_id'], $config['chat_id_admin'], $msg, false);
    } else {
        sendMessage(
            $ctx['bot_id'],
            $ctx['chat_id'],
            "Du bist schon freigebenen, kann losgehen ;) \nMit /auf und /zu kannst du die Tür bedienen.",
            false
        );
    }
}

if ($cmd === '/allowperson' && $ctx['super_private_session']) {
    $config = $ctx['config'];
    $file = logPath($config, '_privateRequest.log');
    $data = explode(':', readFileHandler($file));

    if (count($data) === 2) {
        $userId = $data[1];
        file_put_contents($file, '');

        $fileApproved = logPath($config, '_privateApproved.log');
        file_put_contents($fileApproved, "\n" . $data[0] . ':' . $userId, FILE_APPEND);
        sendMessage(
            $ctx['bot_id'],
            $userId,
            "Bist freigebenen, have fun ;) \nMit /auf und /zu kannst du die Tür bedienen.",
            false
        );
    }
}

if ($cmd === '/allowgroup' && $ctx['super_private_session']) {
    $config = $ctx['config'];
    $file = logPath($config, '_privateRequest.log');
    $data = explode(':', readFileHandler($file));

    if (count($data) === 2) {
        $userId = $data[1];
        file_put_contents($file, '');

        $fileApproved = logPath($config, '_privateApproved.log');
        file_put_contents($fileApproved, "\n" . $data[0] . ':' . $userId, FILE_APPEND);
        $lines = array_unique(file($fileApproved) ?: []);
        file_put_contents($fileApproved, implode($lines));

        sendMessage(
            $ctx['bot_id'],
            $userId,
            "Bist freigebenen, have fun ;) \nMit /auf und /zu kannst du die Tür bedienen.",
            false
        );
    }
}

if (
    ($cmd === '/declineperson' || $cmd === '/declinegroup')
    && $ctx['super_private_session']
) {
    $config = $ctx['config'];
    $file = logPath($config, '_privateRequest.log');
    $data = explode(':', readFileHandler($file));

    if (count($data) === 2) {
        $userId = $data[1];
        file_put_contents($file, '');

        $fileApproved = logPath($config, '_privateApproved.log');
        $lines = array_unique(file($fileApproved) ?: []);
        $userWasApproved = false;

        foreach ($lines as $key => $line) {
            $lineUserId = explode(':', $line)[1] ?? '';
            if (intval($lineUserId) === intval($userId)) {
                unset($lines[$key]);
                $userWasApproved = true;
            }
        }
        file_put_contents($fileApproved, implode($lines));

        $adminMsg = $userWasApproved ? 'Entfernt!' : 'Abgelehnt!';
        sendMessage($ctx['bot_id'], $config['chat_id_admin'], $adminMsg, false);
    }
}

if ($cmd === '/ban') {
    $config = $ctx['config'];
    $fileApproved = logPath($config, '_privateApproved.log');
    $lines = array_unique(file($fileApproved) ?: []);
    $userWasApproved = false;

    foreach ($lines as $key => $line) {
        if (intval($line) === intval($ctx['chat_id'])) {
            unset($lines[$key]);
            $userWasApproved = true;
        }
    }
    file_put_contents($fileApproved, implode($lines));

    $msg = $userWasApproved ? 'Entfernt!' : 'Kenne ich nicht!';
    sendMessage($ctx['bot_id'], $ctx['chat_id'], $msg, false);
}
