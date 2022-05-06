<?php

namespace App\Tests\Entity;

use App\Entity\Interroger;
use PHPUnit\Framework\TestCase;

class InterrogerTest extends TestCase
{
    public function testValide(): void
    {
        $question = New Interroger();
        $question
                ->setIntitule("Vrai")               
                ;

        //voir si les assert sont vrai        
        $this->assertTrue($question->getIntitule() === "Vrai");     
    }

    public function testNonValide(): void
    {
        $question = new Interroger();
        $question
                ->setIntitule("Vrai")                
            ;
        // $this->assertFalse(false);
        $this->assertFalse($question->getIntitule() !== "Vrai");       
    }

    public function testVide(): void
    {
        $question = new Interroger();
        // $this->assertEmpty(empty);
        $this->assertEmpty($question->getId());
        $this->assertEmpty($question->getIntitule());         
    }
}