<?php
namespace App\Enums;

enum OrderStatus : string {
    case PLACED = 'Commande passée';
    case PROCESSING = 'En cours de traitement';
    case CONFIRMED = 'Confirmé';
    case SHIPPED = 'Livré';
    case PAID = 'payé';
    case BACK = 'Retour';
    case DECLINED = 'Annulé';
}
