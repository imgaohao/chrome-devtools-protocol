<?php
require '../vendor/autoload.php';
// context creates deadline for operations
use ChromeDevtoolsProtocol\Context;
use ChromeDevtoolsProtocol\Instance\Launcher;
use ChromeDevtoolsProtocol\Instance\ProcessInstance;
use ChromeDevtoolsProtocol\Model\DOM\QuerySelectorRequest;
use ChromeDevtoolsProtocol\Model\Input\DispatchMouseEventRequest;
use ChromeDevtoolsProtocol\Model\Network\DataReceivedEvent;
use ChromeDevtoolsProtocol\Model\Network\EnableRequest;
use ChromeDevtoolsProtocol\Model\Network\GetResponseBodyRequest;
use ChromeDevtoolsProtocol\Model\Network\RequestWillBeSentEvent;
use ChromeDevtoolsProtocol\Model\Network\ResponseReceivedEvent;
use ChromeDevtoolsProtocol\Model\Page\CaptureScreenshotRequest;
use ChromeDevtoolsProtocol\Model\Page\NavigateRequest;
use ChromeDevtoolsProtocol\Model\Runtime\EvaluateRequest;
use ChromeDevtoolsProtocol\Model\Target\CreateTargetRequest;
use Symfony\Component\Process\Process;

//$path = getenv('PATH');
//putenv("PATH=D:\\Chrome\\chrome-win64\\chrome-win64");
putenv("PATH=C:\\Program Files\\Google\\Chrome\\Application");

$ctx = Context::withTimeout(Context::background(), 300 /* seconds */);

// launcher starts chrome process ($instance)
$launcher = new Launcher(9222);
//$instance = $launcher->launch($ctx, '--remote-allow-origins=*');
$instance = $launcher->launch($ctx, '--remote-allow-origins=*', "--headless");
//$instance = new ProcessInstance(new Process([]), '', 9222);
//$instance->version($ctx);

try {
    $tabs = $instance->tabs($ctx);

    // work with new tab
//    $tab = $instance->open($ctx, 'https://dms.huolala.work');
    $tab = $instance->open($ctx, 'https://v.douyin.com/iRRSTFrb/');
//    $tab = $instance->open($ctx, 'https://www.douyin.com/user/MS4wLjABAAAAu7yxghANo4OMIFkbIa6zsCbqoUn3V1jkKSTKIupXwgijogFRr0PFIiiE-fY-5SGn?vid=7303549310661135651');
//    $tab = $instance->open($ctx, 'https://www.douyin.com/video/7303354348149493003?modeFrom=userLike&secUid=MS4wLjABAAAABjzOKEBgGtGjgevqlbJJuRNcobQVNcznA3B0SiXRPjs');
//    $tab = $instance->open($ctx, 'https://www.douyin.com/video/7303422993462562084');
//    $devtools = $instance->createSession($ctx);
//    $tab->activate($ctx);

    $devtools = $tab->devtools();
//    $target = $devtools->target();
//    $target->createTarget($ctx, CreateTargetRequest::builder()->setBackground(true)->setUrl('https://dms.huolala.work')->build());
    try {
//        $devtools->dom()->enable($ctx);
        $devtools->network()->enable($ctx, EnableRequest::make());
        $devtools->page()->enable($ctx);

        $devtools->page()->navigate($ctx, NavigateRequest::builder()->setUrl("https://dms.huolala.work/")->build());

//        $queryResult = $devtools->dom()->querySelector($ctx, QuerySelectorRequest::builder()->setSelector('/html/body/div[1]/section/main/div/aside/div/ul/li[1]/ul/li[3]/span/span/span[2]')->setNodeId(0)->build());

//        $devtools->input()->dispatchMouseEvent($ctx, DispatchMouseEventRequest::builder()->setType('mousePressed')->setButton('left')->setX(655)->setY(657)->build());

//        $queryResult->
        $requestId = '';

        $devtools->network()->addRequestWillBeSentListener(function (RequestWillBeSentEvent $requestWillBeSentEvent) use ($devtools, $ctx, &$requestId) {
//            var_dump(func_get_args());
//            if (strpos($requestWillBeSentEvent->request->url, '/bizgw/account/hs/') !== false) {
//                echo ($requestWillBeSentEvent->request->headers->get('Token')) . PHP_EOL;
//            }
            if (strpos($requestWillBeSentEvent->request->url, 'aweme/v1/web/aweme/post') !== false) {
                $requestId = $requestWillBeSentEvent->requestId;
            }
            if (strpos($requestWillBeSentEvent->request->url, 'aweme/v1/web/aweme/detail') !== false) {
                $requestId = $requestWillBeSentEvent->requestId;
            }
        });
//        $devtools->network()->addResponseReceivedListener(function (ResponseReceivedEvent $responseReceivedEvent) use ($devtools, $ctx)  {
////            var_dump(func_get_args());
////            if (strpos($responseReceivedEvent->response->url, '/bizgw/mysql/querydata') !== false) {
////            }
//        });
        $has = false;

        $devtools->network()->addDataReceivedListener(function (DataReceivedEvent $dataReceivedEvent)  use ($devtools, $ctx, &$requestId, &$has)  {
//            var_dump(func_get_args());
//            print_r($dataReceivedEvent);
            if ($dataReceivedEvent->requestId == $requestId) {
                try {
                    file_put_contents('D:\wamp64\www\chrome-devtools-protocol-master\mytest\data.json', $devtools->network()->getResponseBody($ctx, GetResponseBodyRequest::builder()->setRequestId($dataReceivedEvent->requestId)->build())->body);
                } catch (Exception $e) {
                }
                echo 222 . PHP_EOL;
                $has = true;
            }
        });
        while (1) {
            $devtools->network()->awaitRequestWillBeSent($ctx);
//            $devtools->network()->awaitResponseReceived($ctx);
            $devtools->network()->awaitDataReceived($ctx);
//            if (!$has) {
//                $devtools->page()->reload($ctx, \ChromeDevtoolsProtocol\Model\Page\ReloadRequest::make());
//            }
            echo 111 . PHP_EOL;
        }
//        $devtools->page()->captureScreenshot($ctx, CaptureScreenshotRequest::builder()->setFormat("jpg")->setQuality(95)->build());
//        $devtools->page()->awaitLoadEventFired($ctx);

        // ... work with page ...
        // e.g.
        // - print to PDF: $devtools->page()->printToPDF($ctx, PrintToPDFRequest::make());
//         - capture screenshot: $devtools->page()->captureScreenshot($ctx, CaptureScreenshotRequest::builder()->setFormat("jpg")->setQuality(95)->build());

    } catch (\Exception $e) {
        echo $e;
    } finally {
        // devtools client needs to be closed
        $devtools->close();
        file_put_contents('D:\wamp64\www\chrome-devtools-protocol-master\mytest\stop.txt', 'end' . date('Y-m-d H:i:s'), FILE_APPEND);
    }

} catch (\Exception $e) {
    echo $e;
}
finally {
    // process needs to be killed
    $instance->close();
    file_put_contents('D:\wamp64\www\chrome-devtools-protocol-master\mytest\stop.txt', 'end2' . date('Y-m-d H:i:s'), FILE_APPEND);
}