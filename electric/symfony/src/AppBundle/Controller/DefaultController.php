<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\ElectricResults;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    
    private function validCalculationParams($point, $count, $chance, $rows, $columns) {
      
      if($point && $count && $chance) {
        
          if(!isset($point["x"]) || !isset($point["y"])) {
            return false;
          }else {
            if(!is_numeric($point["x"]) || !is_numeric($point["y"])) {
              return false;
            }
            if(is_float($point["x"]*1) || is_float($point["y"]*1)) {
              return false;
            }
            if($point["x"] > ($rows - 1) || $point["y"] > ($columns - 1)) {
              return false;
            }
          }
          
          return true;
          
        }else {
          return false;
        }
    }
    
    /**
     * @Route("/calculation", name="calculationajax")
     * @Method({"POST"})
     */
    public function calculationAction(Request $request)
    {
        $responce = [];
        $point = $request->request->get('point');
        $count = $request->request->getInt('count');
        $chance = $request->request->getInt('chance');
        // подачей rows, columns с клиента можно задавать размер сетки(расширение функционала)
        $rows = 5;
        $columns = 5;
        if($this->validCalculationParams($point, $count, $chance, $rows, $columns)) {

            $x = (int)$point["x"];
            $y = (int)$point["y"];
          
            $points = [];
            
            // шанс 1 к 25
            if($count == $chance) {
              $points[-1]["x"] = rand(0, $columns - 1);
              $points[-1]["y"] = rand(0, $rows - 1);
            }
            
            // нулевые точки
            if(($y + 1) < $rows) {
              $points[0]["x"] = $x;
              $points[0]["y"] = $y + 1;    
            } 
            if(($y - 1) >= 0) {   
              $points[1]["x"] = $x;
              $points[1]["y"] = $y - 1;
            }

            // положительная и/или отрицательная стороны
            $y--;
            for($i = 2; $i < $columns; $i++) {
              if(($x + 1) < $columns && $y >= 0 && $y < $rows) {
                $points[$i]["x"] = $x + 1;
                $points[$i]["y"] = $y;
              }
              if(($x - 1) >= 0 && $y >= 0 && $y < $rows) {
                $points[$i + $columns]["x"] = $x - 1;
                $points[$i + $columns]["y"] = $y;
              }
              $y++;
            }
            
            $responce["status"] = true;
            $responce["points"] = $points;
            return new JsonResponse($responce);
        }else {
            $responce["status"] = false;
            $responce["message"] = "not valid parameters";
            return new JsonResponse($responce);
        }
    }
    
    /**
     * @Route("/setresult", name="setresultajax")
     * @Method({"POST"})
     */
    public function setResultAction(Request $request)
    {
        $name = $request->request->get('name');
        $count = $request->request->getInt('count');
        $responce = [];
        
        if($name && $count && strlen($name) < 50) {

          $em = $this->getDoctrine()->getManager();

          try {
            
            $result = new ElectricResults();
            $result->setName($name);
            $result->setCount($count);

            $em->persist($result);
            $em->flush();
            
            $responce["status"] = true;
            $responce["message"] = "result added";
            return new JsonResponse($responce);
            
          } catch (Exception $e) {    
            $responce["status"] = false;
            $responce["message"] = "not result added";
            return new JsonResponse($responce);  
          }
        }else {
          $responce["status"] = false;
          $responce["message"] = "not valid parameters";
          return new JsonResponse($responce);
        }
    }
    
    /**
     * @Route("/bestresults", name="bestresultajax")
     * @Method({"POST"})
     */
    public function bestResultsAction(Request $request)
    {
      $repository = $this->getDoctrine()->getRepository(ElectricResults::class);
      $results = $repository->findBy(array(), array('count' => 'ASC'), 10);
      $responce = [];
      foreach ($results as $key => $result) {
        $responce[$key]["name"] = $result->getName();
        $responce[$key]["count"] = $result->getCount();
      }
      return $this->render('default/results.html.twig', array('results' => $results));
    }
    
}
