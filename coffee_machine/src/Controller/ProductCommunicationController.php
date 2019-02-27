<?php

// src/Controller/ProductCommunicationController.php
// https://docs.microsoft.com/ru-ru/azure/architecture/best-practices/api-design
namespace App\Controller;

use Doctrine\ORM\Query;
use App\Entity\ProductCommunication;
use App\Entity\ProductDictionary;
use App\Entity\PriceDictionary;
use App\Entity\BanknoteDictionary;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductCommunicationController extends AbstractController {

  private $translator;
  private $validator;

  public function __construct(
    TranslatorInterface $translator
  ) {
    $this->translator = $translator;
    $this->validator = Validation::createValidator();
  }

  /**
  * @Route("/communication", name="product_communication_add", methods={"POST"})
  * Добавить связь продукта
  * @param int product_id
  * @param int price_id
  * @param int banknote_id
  * @return json response
  */
  public function addAction(
    Request $request
  ) {
    $product_id = (int)$request->get("product_id");
    $price_id = (int)$request->get("price_id");
    $banknote_id = (int)$request->get("banknote_id");
    $response = array();
    if (!$price_id || !$product_id || !$banknote_id) {
      $response["status"]["code"] = $this->translator->trans("CODE_NO_ALL_PARAMS");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_ALL_PARAMS");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $productCommunication = new ProductCommunication();
    $product = $entityManager->getRepository(ProductDictionary::class)->find($product_id);
    if (!$product) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_PRODUCT");
      return new JsonResponse($response);
    }
    $price = $entityManager->getRepository(PriceDictionary::class)->find($price_id);
    if (!$price) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_PRICE");
      return new JsonResponse($response);
    }
    $banknote = $entityManager->getRepository(BanknoteDictionary::class)->find($banknote_id);
    if (!$banknote) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_BANKNOTE");
      return new JsonResponse($response);
    }
    $productCommunication->setCommunicationProduct($product);
    $productCommunication->setCommunicationPrice($price);
    $productCommunication->setCommunicationBanknote($banknote);
    $productCommunication->setCommunicationActive(true);
    $productCommunication->setCommunicationCreated(new \DateTime());
    $productCommunication->setCommunicationUpdated(new \DateTime());
    $entityManager->persist($productCommunication);
    $entityManager->flush();
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_communication_id"] = $productCommunication->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/communication/{id}", name="product_communication_get", methods={"GET"}, requirements={"id"="\d+"})
  * Получить связь продукта
  * @param int id
  * @return json response
  */
  public function getAction(
    Request $request, $id
  ) {
    $response = array();
    $productCommunication = $this->getDoctrine()
      ->getRepository(ProductCommunication::class)
      ->find($id);
    if (!$productCommunication) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_id"] = $productCommunication->getCommunicationProductId();
    $response["data"]["price_id"] = $productCommunication->getCommunicationPriceId();
    $response["data"]["banknote_id"] = $productCommunication->getCommunicationBanknoteId();
    $response["data"]["product_name"] = $productCommunication->getCommunicationProducts();
    $response["data"]["price_cost"] = $productCommunication->getCommunicationPrices();
    $response["data"]["banknote_name"] = $productCommunication->getCommunicationBanknotes();
    return new JsonResponse($response);
  }

  /**
  * @Route("/communication", name="product_communication_list", methods={"GET"})
  * Получить все связи продуктов
  * @return json response
  */
  public function listAction() {
    $response = array();
    $productsCommunication = $this->getDoctrine()
      ->getRepository(ProductCommunication::class)
      ->createQueryBuilder("c")
      ->getQuery()
      ->getResult(Query::HYDRATE_ARRAY);
    if (!$productsCommunication ) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCTS_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["products_communication"] = $productsCommunication;
    return new JsonResponse($response);
  }

  /**
  * @Route("/communication/{id}", name="product_communication_update", methods={"PUT"}, requirements={"id"="\d+"})
  * Обновить связь продукта
  * @param int id
  * @param int product_id
  * @param int price_id
  * @param int banknote_id
  * @param int active (active == 0 ? false : true)
  * @return json response
  */
  public function updateAction(
    Request $request, $id
  ) {
    $product_id = (int)$request->get("product_id");
    $price_id = (int)$request->get("price_id");
    $banknote_id = (int)$request->get("banknote_id");
    $active = (int)$request->get("active") == 0 ? false: true;
    $response = array();
    if (!$product_id || !$price_id || !$banknote_id) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $productCommunication = $entityManager->getRepository(ProductCommunication::class)->find($id);
    if (!$productCommunication) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $productCommunication->setProductId($product_id);
    $productCommunication->setPriceId($price_id);
    $productCommunication->setBanknoteId($banknote_id);
    $productCommunication->setCommunicationActive($active);
    $productCommunication->setCommunicationUpdated(new \DateTime());
    $entityManager->flush();
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_communication_id"] = $productCommunication->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/communication/{id}", name="product_communication_delete", methods={"DELETE"}, requirements={"id"="\d+"})
  * Удалить связь продукта
  * @param int id
  * @return json response
  */
  public function deleteAction($id) {
    $response = array();
    $entityManager = $this->getDoctrine()->getManager();
    $productCommunication = $entityManager->getRepository(ProductCommunication::class)->find($id);
    if (!$productCommunication) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager->remove($productCommunication);
    $entityManager->flush();
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_communication_id"] = $productCommunication->getId();
    return new JsonResponse($response);
  }
}
