<?php

// src/Controller/BanknoteDictionaryController.php
// https://docs.microsoft.com/ru-ru/azure/architecture/best-practices/api-design
namespace App\Controller;

use Doctrine\ORM\Query;
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

class BanknoteDictionaryController extends AbstractController {

  private $translator;
  private $validator;

  public function __construct(
    TranslatorInterface $translator
  ) {
    $this->translator = $translator;
    $this->validator = Validation::createValidator();
  }

  /**
  * @Route("/banknote", name="banknote_add", methods={"POST"})
  * Добавить валюту в справочник
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
    $banknote = new BanknoteDictionary();
    $banknote->setBanknoteName($name);
    $banknote->setBanknoteActive(true);
    $banknote->setBanknoteCreated(new \DateTime());
    $banknote->setBanknoteUpdated(new \DateTime());
    $entityManager->persist($banknote);
    try {
      $entityManager->flush();
    } catch (\Exception $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_ADD_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["banknote_id"] = $banknote->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/banknote/{id}", name="banknote_get", methods={"GET"}, requirements={"id"="\d+"})
  * @param int id
  * @return json response
  */
  public function getAction(
    Request $request, $id
  ) {
    $response = array();
    $banknote = $this->getDoctrine()
      ->getRepository(BanknoteDictionary::class)
      ->find($id);
    if (!$banknote) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_BANKNOTE_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["banknote_name"] = $banknote->getBanknoteName();
    return new JsonResponse($response);
  }

  /**
  * @Route("/banknote", name="banknote_list", methods={"GET"})
  * @return json response
  */
  public function listAction() {
    $response = array();
    $banknotes = $this->getDoctrine()
      ->getRepository(BanknoteDictionary::class)
      ->createQueryBuilder("c")
      ->getQuery()
      ->getResult(Query::HYDRATE_ARRAY);
    if (!$banknotes) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_BANKNOTES_NOT_FOUND");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["banknotes"] = $banknotes;
    return new JsonResponse($response);
  }

  /**
  * @Route("/banknote/put/{id}", name="banknote_update", methods={"POST", "GET"}, requirements={"id"="\d+"})
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
      $response["status"]["message"] = $this->translator->trans("MESSAGE_BANKNOTE_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $banknote = $entityManager->getRepository(BanknoteDictionary::class)->find($id);
    if (!$banknote) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_BANKNOTE_NOT_FOUND");
      return new JsonResponse($response);
    }
    $banknote->setBanknoteName($name);
    $banknote->setBanknoteActive(true);
    $banknote->setBanknoteUpdated(new \DateTime());
    try {
      $entityManager->flush();
    } catch (\Exception $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_UPDATE_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["banknote_id"] = $banknote->getId();
    return new JsonResponse($response);
  }

  /**
  * @Route("/banknote/{id}", name="banknote_delete", methods={"DELETE"}, requirements={"id"="\d+"})
  * @param int id
  * @return json response
  */
  public function deleteAction($id) {
    $response = array();
    $entityManager = $this->getDoctrine()->getManager();
    $banknote = $entityManager->getRepository(BanknoteDictionary::class)->find($id);
    if (!$banknote) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_BANKNOTE_NOT_FOUND");
      return new JsonResponse($response);
    }
    $entityManager->remove($banknote);
    try {
      $entityManager->flush();
    } catch (\Exception $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_FAILD_DELETE_RECORD");
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["banknote_id"] = $banknote->getId();
    return new JsonResponse($response);
  }
}
