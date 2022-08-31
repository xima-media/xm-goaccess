<?php

return [
    'manual-download-pdf' => [
        'path' => '/XmManualManual/download',
        'access' => 'public',
        'target' => \Xima\XmManual\Controller\DownloadController::class . '::downloadPdf',
    ],
];
