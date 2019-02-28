<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
      $this->amountAroundPoints();
      $this->calculationValidParams();
      $this->setResultValidParams();
      $this->dotOutside();
    }
    
    /**
     * Количество вокруг точки
     */
    public function amountAroundPoints() {
      
      $client = static::createClient();
      
      $points = [];
      $counts = [];
      
      for($i = 0; $i < 5; $i++) {
        for($j = 0; $j < 5; $j++) {
          if($i == 0 || $i == 4) {
            $points[] = ["x" => $j, "y" => $i];
            if($j == 0 || $j == 4) {
              $counts[] = 3;
            }else {
              $counts[] = 5;
            }
          }else {
            $points[] = ["x" => $j, "y" => $i];
            if($j == 0 || $j == 4) {
              $counts[] = 5;
            }else {
              $counts[] = 8;
            }
          }
        }
      }
         
      foreach ($points as $key => $point) {

          $client->request('POST', '/calculation', ["point" => $point, "count" => 2, "chance" => 3]);
      
          $responce = json_decode($client->getResponse()->getContent());

          $this->assertEquals($counts[$key], count((array)$responce->points));
        
      }
      
    }
    
    /**
      * Точка за пределами сетки
      */
    public function dotOutside() {
      $client = static::createClient();
      $client->request('POST', '/calculation', ["point" => ["x" => 6, "y" => 6], "count" => 2, "chance" => 3]);
  
      $responce = json_decode($client->getResponse()->getContent());
      $this->assertEquals(false, $responce->status);
    }
    
    /**
      * Валидация параметров метода calculation
      */
    public function calculationValidParams() {
      
      $client = static::createClient();
      
      // параметр point
      $testCase = [
        ["x" => "test", "y" => 0],
        ["x" => 0, "y" => "test"],
        ["x" => "test", "y" => "test"],
        ["x" => [], "y" => "test"],
        ["x" => "test", "y" => []],
        ["x" => [], "y" => []],
        ["x" => true, "y"=> false],
        ["x" => 1.5, "y" => 0.5],
        [], true, false, 1.4, 0, "test"
      ];
      
      foreach ($testCase as $key => $point) {
        $client->request('POST', '/calculation', ["point" => $point, "count" => 2, "chance" => 3]);
        $responce = json_decode($client->getResponse()->getContent());
        $this->assertEquals(false, $responce->status);
      }
      
      // не заданы обязательные параметры
      $testCase = [
        ["point" => ["x" => 0, "y" => 1], "chance" => 3],
        ["point" => ["x" => 0, "y" => 1], "count" => 2],
        ["count" => 2, "chance" => 3],
        ["point" => ["x" => 0, "y" => 1]],
        ["chance" => 3],
        ["count" => 2],
        []
      ];
      
      foreach ($testCase as $data) {
        $client->request('POST', '/calculation', $data);
        $responce = json_decode($client->getResponse()->getContent());
        $this->assertEquals(false, $responce->status);
      }
      
      // верная подача параметров
      $client->request('POST', '/calculation', ["point" => ["x"=>0, "y"=>1], "count" => 2.5, "chance" => 3.5]);
      $responce = json_decode($client->getResponse()->getContent());
      $this->assertEquals(true, $responce->status);
      
    }
    
    /**
      * Валидация параметров метода setResult
      */
    public function setResultValidParams() {
      
      $client = static::createClient();
      
      // не верная подача параметров
      $s = "";
      for($i = 0; $i < 50; $i++) {
        $s .= $i;
      }
      
      $testCase = [
        ["name" => "0", "count" => 3],
        ["name" => [], "count" => 3],
        ["name" => "test", "count" => []],
        ["name" => "test", "count" => "test"],
        ["name" => $s, "count" => 50],
        ["name" => "test"],
        ["count" => 3],
        []
      ];
      
      foreach ($testCase as $data) {
        $client->request('POST', '/setresult', $data);
        $responce = json_decode($client->getResponse()->getContent());
        $this->assertEquals(false, $responce->status);
      }
      
      // верная подача параметров
      $client->request('POST', '/setresult', ["name" => "test", "count" => 3]);
      $responce = json_decode($client->getResponse()->getContent());
      $this->assertEquals(true, $responce->status);
      
    }
    
    /**
      * bestResultsValid
      */
    public function bestResultsValid() {
      
      $client->request('POST', '/bestresults');
      $responce = json_decode($client->getResponse()->getContent());
      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      
    }
    
}
