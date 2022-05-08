<?php

namespace App\Tests\Entity;

use App\Entity\Reponse;
use DateTime;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testValide(): void
    {
        $date = new DateTime();
        $reponse = New Reponse();
        $reponse
                ->setRepondant("Fabrice")
                ->setCommentaire("C'est nul")
                ->setEmail("Valdo@toto.fr")
                ->setAcceptationpartagedonnee(true)
                ->setDatecreation($date)
                ->setDatemiseajour($date)
                ->setDatefermeture($date)
                ;

        //voir si les assert sont vrai        
        $this->assertTrue($reponse->getRepondant() === "Fabrice");
        $this->assertTrue($reponse->getCommentaire() === "C'est nul");     
        $this->assertTrue($reponse->getEmail() === "Valdo@toto.fr");     
        $this->assertTrue($reponse->getAcceptationpartagedonnee() === true);     
        $this->assertTrue($reponse->getDatecreation() === $date);     
        $this->assertTrue($reponse->getDatemiseajour() === $date);     
        $this->assertTrue($reponse->getDatefermeture() === $date);   
    }

    public function testNonValide(): void
    {
        $date = new DateTime();
        $reponse = new Reponse();
        $reponse
            ->setRepondant("Fabrice")
            ->setCommentaire("C'est nul")
            ->setEmail("Valdo@toto.fr")
            ->setAcceptationpartagedonnee(true)
            ->setDatecreation($date)
            ->setDatemiseajour($date)
            ->setDatefermeture($date)
            ;
        // $this->assertFalse(false);
        $this->assertFalse($reponse->getRepondant() !== "Fabrice");
        $this->assertFalse($reponse->getCommentaire() !== "C'est nul");     
        $this->assertFalse($reponse->getEmail() !== "Valdo@toto.fr");     
        $this->assertFalse($reponse->getAcceptationpartagedonnee() !== true);     
        $this->assertFalse($reponse->getDatecreation() !== $date);     
        $this->assertFalse($reponse->getDatemiseajour() !== $date);     
        $this->assertFalse($reponse->getDatefermeture() !== $date);       
    }

    public function testVide(): void
    {
        $date = new DateTime();
        $reponse = new Reponse();
        // $this->assertEmpty(empty);
        $this->assertEmpty($reponse->getId());
        $this->assertEmpty($reponse->getRepondant());
        $this->assertEmpty($reponse->getCommentaire());     
        $this->assertEmpty($reponse->getEmail());     
        $this->assertEmpty($reponse->getAcceptationpartagedonnee());     
        $this->assertEmpty($reponse->getDatecreation());     
        $this->assertEmpty($reponse->getDatemiseajour());     
        $this->assertEmpty($reponse->getDatefermeture());
    }
}