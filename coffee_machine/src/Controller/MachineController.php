<?php

// src/Controller/MachineController.php
// https://docs.microsoft.com/ru-ru/azure/architecture/best-practices/api-design
namespace App\Controller;

use Doctrine\ORM\Query;
use App\Entity\ProductCommunication;
use App\Entity\StorageMachine;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MachineController extends AbstractController {

  private $translator;
  private $validator;
  private $accountLimit = true;
  private $accountLimitSumm = 150000;

  public function __construct(
    TranslatorInterface $translator
  ) {
    $this->translator = $translator;
    $this->validator = Validation::createValidator();
  }

  /**
  * @Route("/payment", name="machine_payment", methods={"POST", "GET"})
  */
  public function paymentAction(
    Request $request
  ) {

    /**
      * Проверки:
      * - Существование параметров (возврат кэша, если взнос был подан в неизвестной валюте
      * возврат кэша, если монеты не загружены, целое количество монет)
      * - Существование продукта (возврат кэша, если продукта не существует)
      * - Проверка поддержки продуктов банкнота (возврат кэша, если не поддерживает)
      * - Проверка необходимого взноса (возврат кэша, если недостаточно взноса)
      * - Проверка существования главого счета (возврат кэша, если главного счета нет)
      * - Проверка существования необходимого количества средств на главном
      * счете для выдачи сдачи (возврат кэша, если недостаточно средств)
      * - Проверка существования необходимого лимита на главном счете
      * (возврат средств, если меньше лимита. опционально)
      **/

    $contribution = $request->get("contribution");
    $banknoteId = (int)$request->get("banknote_id");
    $productCommunicationId = (int)$request->get("product_communication_id");
    $response = array();
    if (!$contribution) {
      $response["status"]["code"] = $this->translator->trans("CODE_NO_ALL_PARAMS");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_ALL_PARAMS");
      return new JsonResponse($response);
    }
    if (filter_var($contribution, FILTER_VALIDATE_INT) === false) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NOT_WHOLE_NUMBER_COINS");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    } else {
      $contribution = (int)$contribution;
    }
    if (!$banknoteId || !$productCommunicationId) {
      $response["status"]["code"] = $this->translator->trans("CODE_NO_ALL_PARAMS");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NO_ALL_PARAMS");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    $productCommunication = $this->getDoctrine()
      ->getRepository(ProductCommunication::class)
      ->find($productCommunicationId);
    if (!$productCommunication) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_NONEXISTENT_PRODUCT");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    if ($banknoteId != $productCommunication->getBanknoteId()) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_INVALID_COIN");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    $productPrice = $productCommunication->getCommunicationPrice()->getPrice();
    if ($contribution < $productPrice) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_INVALID_SUMM");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    $entityManager = $this->getDoctrine()->getManager();
    $storageMachine = $entityManager->getRepository(StorageMachine::class)->find(1);
    if (!$storageMachine) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_MAIN_ACCOUNT_NOT_FOUND");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    $totalCoins = $storageMachine->getTotalCoins();
    if ($this->accountLimit) {
      if ($totalCoins < $this->accountLimitSumm) {
        $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
        $response["status"]["message"] = $this->translator->trans("MESSAGE_INSUFFICIENT_FUNDS_ON_MAIN_ACCOUNT");
        $response["data"]["cashback"] = $contribution;
        return new JsonResponse($response);
      }
    }
    $cashback = $contribution - $productPrice;
    if ($totalCoins < $cashback) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_INSUFFICIENT_FUNDS_ON_MAIN_ACCOUNT");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    $totalCoins += $productPrice;
    $storageMachine->setTotalCoins($totalCoins);
    try {
      $entityManager->flush();
    } catch (\Exception $e) {
      $response["status"]["code"] = $this->translator->trans("CODE_ERROR");
      $response["status"]["message"] = $this->translator->trans("MESSAGE_TRANSFER_ERROR_TO_ACCOUNT");
      $response["data"]["cashback"] = $contribution;
      return new JsonResponse($response);
    }
    $response["status"]["code"] = $this->translator->trans("CODE_OK");
    $response["status"]["message"] = $this->translator->trans("MESSAGE_OK");
    $response["data"]["product"] = $productCommunication->getCommunicationProduct()->getId();
    $response["data"]["cashback"] = $cashback;
    return new JsonResponse($response);
  }
}
