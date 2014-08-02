<?php

return array(

    /*
     |--------------------------------------------------------------------------
     | Psysh Configuration Settings
     |--------------------------------------------------------------------------
     */
    'configuration' => array(

        // 'defaultIncludes' => array(),
        // 'useReadline' => true,
        // 'usePcntl' => true,
        // 'codeCleaner' => null,
        // 'pager' => null,
        // 'loop' => null,
        // 'tempDir' => null,
        // 'manualDbFile' => '',
        // 'presenters' => array(),
        // 'historySize' => 0,
        // 'eraseDuplicates' => true,
        // 'commands' => array(),
        // 'complementer' => null,
        // 'contributors' => array(),

    ),

    /*
     |--------------------------------------------------------------------------
     | Contributor Settings
     |--------------------------------------------------------------------------
     */
    'contributor' => array(

        'model' => array(

            'path' => app_path().'/models',
            'recursive' => true,

        ),

    ),

);
