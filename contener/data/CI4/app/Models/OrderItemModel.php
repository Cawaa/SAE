<?php

namespace App\Models;

use CodeIgniter\Model;

// Modèle pour gérer les éléments de commande.
class OrderItemModel extends Model
{
    protected $table      = 'order_items';
    protected $primaryKey = ''; // composite
    protected $returnType = 'array';

    // Champs autorisés pour l'insertion et la mise à jour.
    protected $allowedFields = ['order_id','beat_id','price'];
    protected $useTimestamps = false;
}
