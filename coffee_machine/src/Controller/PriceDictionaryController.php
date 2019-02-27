<?php

// src/Controller/PriceDictionaryController.php
// https://docs.microsoft.com/ru-ru/azure/architecture/best-practices/api-design
namespace App\Controller;

use Doctrine\ORM\Query;
use App\Entity\PriceDictionary;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PriceDictionaryController extends AbstractController {

  private $translator;
  private $validator;

  public function __construct(
    TranslatorInterface $translator
  ) {
    $this->translator = $translator;
    $this->validator = Validation::createValidator();
  }

  /**
  * @Route("/price", name="price_add", methods={"POST"})
  * Добавить цену в справочник
  * @param int cost
  * @return json response
  */
  public function addAction(
    Request $request
  ) {
    $cost = (int)$request->get("cost");
    $response = array();
    if (!$cost) {
      $response["status"]["code"] = $this->translator->trans("CODE_NO_ALL_PARAMS");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_ALL_PARAMS");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $price = new PriceDictionary();
    $price->setPrice($cost);
    $price->setPriceActive(true);
    $price->setPriceCreated(new \DateTime());
    $price->setPriceUpdated(new \DateTime());
    $entityManager->persist($price);
    try {
      $entityManager->flush();
    } catch (ORMException $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_ADD_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["price_id"] = $price->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/price/{id}", name="price_get", methods={"GET"}, requirements={"id"="\d+"})
  * @param int id
  * @return json response
  */
  public function getAction(
    Request $request, $id
  ) {
    $response = array();
    $price = $this->getDoctrine()
      ->getRepository(PriceDictionary::class)
      ->find($id);
    if (!$price) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["price_cost"] = $price->getPrice();
    return new JsonResponse($response);
  }

  /**
  * @Route("/price", name="price_list", methods={"GET"})
  * @return json response
  */
  public function listAction() {
    $response = array();
    $prices = $this->getDoctrine()
      ->getRepository(PriceDictionary::class)
      ->createQueryBuilder("c")
      ->getQuery()
      ->getResult(Query::HYDRATE_ARRAY);
    if (!$prices) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCTS_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["prices"] = $prices;
    return new JsonResponse($response);
  }

  /**
  * @Route("/price/put/{id}", name="price_update", methods={"POST", "GET"}, requirements={"id"="\d+"})
  * @param int id
  * @param int cost
  * @param int active (active == 0 ? false : true)
  * @return json response
  */
  public function updateAction(
    Request $request, $id
  ) {
    $response = array();
    $cost = (int)$request->get("cost");
    $active = (int)$request->get("active") == 0 ? false: true;
    if (!$cost) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $price = $entityManager->getRepository(PriceDictionary::class)->find($id);
    if (!$price) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $price->setPrice($cost);
    $price->setPriceActive($active);
    $price->setPriceUpdated(new \DateTime());
    try {
      $entityManager->flush();
    } catch (ORMException $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_UPDATE_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["price_id"] = $price->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/price/{id}", name="price_delete", methods={"DELETE"}, requirements={"id"="\d+"})
  * Удалить цену из справочника
  * @param int id
  * @return json response
  */
  public function deleteAction($id) {
    $response = array();
    $entityManager = $this->getDoctrine()->getManager();
    $price = $entityManager->getRepository(PriceDictionary::class)->find($id);
    if (!$price) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager->remove($price);
    try {
      $entityManager->flush();
    } catch (ORMException $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_DELETE_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["price_id"] = $price->getId();
    return new JsonResponse($response);
  }
}
