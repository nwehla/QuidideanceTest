<?php

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use DateTime;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testValide(): void
    {
        $date = new DateTime();
        $utilisateur = New Utilisateur();
        $utilisateur
                ->setEmail("Valdo@toto.fr")
                ->setPassword("Valdo@1974")
                ->setRoles(["ROLE_SUPERADMIN"])
                ->setDatecreation($date)
                ->setDatemiseajour($date)
                ->setUsername("Valéry")
                ->setActivateToken("azertyuiopqsd")               
                ;

        //voir si les assert sont vrai        
        $this->assertTrue($utilisateur->getEmail() === "Valdo@toto.fr");
        $this->assertTrue($utilisateur->getPassword() === "Valdo@1974");     
        $this->assertTrue($utilisateur->getRoles() === ["ROLE_SUPERADMIN"]);     
        $this->assertTrue($utilisateur->getDatecreation() === $date);     
        $this->assertTrue($utilisateur->getDatemiseajour() === $date);     
        $this->assertTrue($utilisateur->getUsername() === "Valéry");     
        $this->assertTrue($utilisateur->getActivateToken() === "azertyuiopqsd");   
    }

    public function testNonValide(): void
    {
        $date = new DateTime();
        $utilisateur = new Utilisateur();
        $utilisateur
            ->setEmail("Valdo@toto.fr")
            ->setPassword("Valdo@1974")
            ->setRoles(["ROLE_SUPERADMIN"])
            ->setDatecreation($date)
            ->setDatemiseajour($date)
            ->setUsername("Valéry")
            ->setActivateToken("azertyuiopqsd")
            ;
        // $this->assertFalse(false);
        $this->assertFalse($utilisateur->getEmail() !== "Valdo@toto.fr");
        $this->assertFalse($utilisateur->getPassword() !== "Valdo@1974");     
        $this->assertFalse($utilisateur->getRoles() !== ["ROLE_SUPERADMIN"]);     
        $this->assertFalse($utilisateur->getDatecreation() !== $date);     
        $this->assertFalse($utilisateur->getDatemiseajour() !== $date);     
        $this->assertFalse($utilisateur->getUsername() !== "Valéry");     
        $this->assertFalse($utilisateur->getActivateToken() !== "azertyuiopqsd");        
    }

    public function testVide(): void
    {
        $date = new DateTime();
        $utilisateur = new Utilisateur();
        // $this->assertEmpty(empty);
        $this->assertEmpty($utilisateur->getId());
        $this->assertEmpty($utilisateur->getEmail());
        $this->assertEmpty($utilisateur->getPassword());
        $this->assertEmpty($utilisateur->getRoles());
        $this->assertEmpty($utilisateur->getDatecreation());
        $this->assertEmpty($utilisateur->getDatemiseajour());
        $this->assertEmpty($utilisateur->getUsername());
        $this->assertEmpty($utilisateur->getActivateToken());
    }
}