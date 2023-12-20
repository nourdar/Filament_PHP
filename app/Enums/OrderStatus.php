<?php
namespace App\Enums;

enum OrderStatus : string {
    case PENDING = 'En attente';
    case PROCESSING = 'En cours de traitement';
    case COMPLETED = 'Validé';
    case DECLINED = 'Décliné';
}
