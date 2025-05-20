<?php
// src/Service/MockPaymentService.php
namespace App\Service;

class MockPaymentService
{
    public function charge(float $amount): bool
    {
        // Ici on simule toujours un succès.
        return true;
    }
}
