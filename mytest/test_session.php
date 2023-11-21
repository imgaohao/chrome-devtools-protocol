<?php

use ChromeDevtoolsProtocol\Context;
use ChromeDevtoolsProtocol\Instance\Launcher;

//putenv("PATH=D:\\Chrome\\chrome-win64\\chrome-win64");
putenv("PATH=C:\\Program Files\\Google\\Chrome\\Application");


$ctx = Context::withTimeout(Context::background(), 300);
$launcher = new Launcher(9222);
$instance = $launcher->launch($ctx, '--remote-allow-origins=*');
//$instance = $launcher->launch($ctx, '--remote-allow-origins=*', "--headless");

try {
    $session = $instance->createSession($ctx);
    try {
        // $session implements DevtoolsClientInterface, same as returned from Tab::devtools()
    } finally {
        $session->close();
    }
} finally {
    $instance->close();
}