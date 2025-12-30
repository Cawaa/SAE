<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        // Ajoutez ici vos champs personnalisés si vous en avez (ex: 'username')
        // Shield gère déjà 'email' et 'password'
        $this->allowedFields = array_merge($this->allowedFields, [
            'username', 
        ]);
    }
}
