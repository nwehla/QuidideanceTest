<?php

namespace App\Tests\Entity;

use App\Entity\Sondage;
use DateTime;
use PHPUnit\Framework\TestCase;

class SondageTest extends TestCase
{
    public function testValide(): void
    {
        $date = new DateTime();
        $sondage = New Sondage();
        $sondage
                ->setTitre("Mon titre")
                ->setQuestion("Es ce utilise ?")
                ->setDescription("Voici la description de ce document")
                ->setMultiple(true)
                ->setStatut("Ouvert")
                ->setMessagefermeture("Le topic est fermé merci à vous")
                ->setDatecreation($date)
                ->setDatemiseajour($date)
                ->setDatedefermeture($date)
                ;

        //voir si les assert sont vrai        
        $this->assertTrue($sondage->getTitre() === "Mon titre");
        $this->assertTrue($sondage->getQuestion() === "Es ce utilise ?");
        $this->assertTrue($sondage->getDescription() === "Voici la description de ce document");
        $this->assertTrue($sondage->getMultiple() === true);     
        $this->assertTrue($sondage->getStatut() === "Ouvert");     
        $this->assertTrue($sondage->getMessagefermeture() === "Le topic est fermé merci à vous");     
        $this->assertTrue($sondage->getDatecreation() === $date);     
        $this->assertTrue($sondage->getDatemiseajour() === $date);     
        $this->assertTrue($sondage->getDatedefermeture() === $date);
    }

    public function testNonValide(): void
    {
        $date = new DateTime();
        $sondage = new Sondage();
        $sondage
            ->setTitre("Mon titre")
            ->setQuestion("Es ce utilise ?")
            ->setDescription("Voici la description de ce document")
            ->setMultiple(true)
            ->setStatut("Ouvert")
            ->setMessagefermeture("Le topic est fermé merci à vous")
            ->setDatecreation($date)
            ->setDatemiseajour($date)
            ->setDatedefermeture($date)
            ;
        // $this->assertFalse(false);
        $this->assertFalse($sondage->getTitre() !== "Mon titre");
        $this->assertFalse($sondage->getQuestion() !== "Es ce utilise ?");
        $this->assertFalse($sondage->getDescription() !== "Voici la description de ce document");
        $this->assertFalse($sondage->getMultiple() !== true);     
        $this->assertFalse($sondage->getStatut() !== "Ouvert");     
        $this->assertFalse($sondage->getMessagefermeture() !== "Le topic est fermé merci à vous");     
        $this->assertFalse($sondage->getDatecreation() !== $date);     
        $this->assertFalse($sondage->getDatemiseajour() !== $date);     
        $this->assertFalse($sondage->getDatedefermeture() !== $date);
    }

    public function testVide(): void
    {
        $date = new DateTime();
        $sondage = new Sondage();
        // $this->assertEmpty(empty);
        $this->assertEmpty($sondage->getId());
        $this->assertEmpty($sondage->getTitre());
        $this->assertEmpty($sondage->getQuestion());
        $this->assertEmpty($sondage->getDescription());
        $this->assertEmpty($sondage->getMultiple());     
        $this->assertEmpty($sondage->getStatut());     
        $this->assertEmpty($sondage->getMessagefermeture());     
        $this->assertEmpty($sondage->getDatecreation());     
        $this->assertEmpty($sondage->getDatemiseajour());     
        $this->assertEmpty($sondage->getDatedefermeture());
    }
}