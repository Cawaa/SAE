<?php
namespace App\Controllers;
use App\Models\ProduitModel;

class Boutique extends BaseController {
    public function index() {
        $model = new ProduitModel();
        
        // On récupère tous les produits
        $data['produits'] = $model->findAll();
        $data['titre'] = "Mon Catalogue";

        return view('list_produit', $data);
    }
}