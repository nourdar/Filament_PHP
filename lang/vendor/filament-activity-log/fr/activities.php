<?php

return [
    'breadcrumb' => 'Historique',

    'title' => 'Historique :record',

    'default_datetime_format' => 'Y-m-d, H:i:s',

    'table' => [
        'field' => 'Colonne',
        'old' => 'Ancien',
        'new' => 'Actuelle',
        'restore' => 'Reset',
    ],

    'events' => [
        'updated' => 'Mettre a jour',
        'created' => 'Créer',
        'deleted' => 'Supprimer',
        'restored' => 'Récupéré',
        'restore_successful' => 'Récupéré avec succès',
        'restore_failed' => 'Échec de la restauration',
    ],
];
