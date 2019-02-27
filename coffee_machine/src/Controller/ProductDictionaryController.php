<?php

// src/Controller/ProductDictionaryController.php
// https://docs.microsoft.com/ru-ru/azure/architecture/best-practices/api-design
namespace App\Controller;

use Doctrine\ORM\Query;
use App\Entity\ProductDictionary;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductDictionaryController extends AbstractController {

  private $translator;
  private $validator;

  public function __construct(
    TranslatorInterface $translator
  ) {
    $this->translator = $translator;
    $this->validator = Validation::createValidator();
  }

  /**
  * @Route("/product", name="product_add", methods={"POST"})
  * Добавить продукт в справочник
  * @param string name
  * @return json response
  */
  public function addAction(
    Request $request
  ) {
    $name = $request->get("name");
    $response = array();
    if (!$name) {
      $response["status"]["code"] = $this->translator->trans("CODE_NO_ALL_PARAMS");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_ALL_PARAMS");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $product = new ProductDictionary();
    $product->setProductName($name);
    $product->setProductActive(true);
    $product->setProductCreated(new \DateTime());
    $product->setProductUpdated(new \DateTime());
    $entityManager->persist($product);
    try {
      $entityManager->flush();
    } catch (ORMException $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_ADD_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_id"] = $product->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/product/{id}", name="product_get", methods={"GET"}, requirements={"id"="\d+"})
  * Получить продукт из справочника
  * @param int id
  * @return json response
  */
  public function getAction(
    Request $request, $id
  ) {
    $response = array();
    $product = $this->getDoctrine()
      ->getRepository(ProductDictionary::class)
      ->find($id);
    if (!$product) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_name"] = $product->getProductName();
    return new JsonResponse($response);
  }

  /**
  * @Route("/product", name="product_list", methods={"GET"})
  * Получить все продукты
  * @return json response
  */
  public function listAction() {
    $response = array();
    $products = $this->getDoctrine()
      ->getRepository(ProductDictionary::class)
      ->createQueryBuilder("c")
      ->getQuery()
      ->getResult(Query::HYDRATE_ARRAY);
    if (!$products) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCTS_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["products"] = $products;
    return new JsonResponse($response);
  }

  /**
  * @Route("/product/put/{id}", name="product_update", methods={"POST", "GET"}, requirements={"id"="\d+"})
  * Обновить продукт
  * @param int id
  * @param string name
  * @param int active (active == 0 ? false : true)
  * @return json response
  */
  public function updateAction(
    Request $request, $id
  ) {
    $response = array();
    $name = $request->get("name");
    $active = (int)$request->get("active") == 0 ? false: true;
    if (!$name) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(ProductDictionary::class)->find($id);
    if (!$product) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $product->setProductName($name);
    $product->setProductActive($active);
    $product->setProductUpdated(new \DateTime());
    try {
      $entityManager->flush();
    } catch (ORMException $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_UPDATE_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product_id"] = $product->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/product/{id}", name="product_delete", methods={"DELETE"}, requirements={"id"="\d+"})
  * Удалить продукт
  * @param int id
  * @return json response
  */
  public function deleteAction($id) {
    $response = array();
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(ProductDictionary::class)->find($id);
    if (!$product) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_PRODUCT_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager->remove($product);
    try {
      $entityManager->flush();
    } catch (ORMException $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_DELETE_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    return new JsonResponse($response);
  }
}
