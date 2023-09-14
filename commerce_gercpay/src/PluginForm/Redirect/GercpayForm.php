<?php

namespace Drupal\commerce_gercpay\PluginForm\Redirect;

use Drupal\commerce_payment\PluginForm\PaymentOffsiteForm as BasePaymentOffsiteForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_gercpay\api\GercpayAPI;
use Drupal\Core\Url;

/**
 * Generates GercPay payment form.
 *
 * Class GercpayForm.
 *
 * @package Drupal\commerce_gercpay\PluginForm\Redirect
 */
class GercpayForm extends BasePaymentOffsiteForm {

  /**
   * {@inheritdoc}
   *
   * @throws \exception
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildConfigurationForm($form, $form_state);
    $api = new GercpayAPI();

    /** @var \Drupal\commerce_payment\Entity\PaymentInterface $payment */
    $payment = $this->entity;
    if ($payment->getPaymentGateway() === NULL) {
      throw new \exception(
        t("Error: for payment @id not found Payment Gateway!", ["@id" => $payment->id()])
      );
    }
    $payment_gateway_plugin = $payment->getPaymentGateway()->getPlugin();

    // Get GercPay module config data.
    $config = $payment_gateway_plugin->getConfiguration();
    $redirect_method = 'post';

    $option = [];
    $amount_entity = $payment->getAmount();
    if ($amount_entity === NULL) {
      throw new \exception(t('Error receiving the payment amount!'));
    }

    $amount   = number_format($amount_entity->getNumber(), 2, '.', '');
    $currency = $amount_entity->getCurrencyCode();

    $order_id = $payment->getOrderId();
    $address  = $payment->getOrder()->getBillingProfile()->get('address')->getValue()[0];

    $client_first_name = $address['given_name'] ?? '';
    $client_last_name  = $address['family_name'] ?? '';

    $description = t('Payment by card on the site') . ' ' . htmlspecialchars($_SERVER["HTTP_HOST"]) .
        ", $client_first_name $client_last_name.";

    $approve_url = Url::FromRoute('commerce_payment.checkout.return',
        ['step' => 'payment', 'commerce_order' => $payment->getOrderId()],
        ['absolute' => TRUE]
    )->toString();
    $decline_url = Url::FromRoute('commerce_payment.checkout.cancel',
        ['step' => 'payment', 'commerce_order' => $payment->getOrderId()],
        ['absolute' => TRUE]
    )->toString();
    $cancel_url = Url::FromRoute('commerce_payment.checkout.cancel',
        ['step' => 'payment', 'commerce_order' => $payment->getOrderId()],
        ['absolute' => TRUE]
    )->toString();
    $callback_url = $payment_gateway_plugin->getNotifyUrl()->toString();

    $language = $config['language'] ?? 'ua';

    $email = $payment->getOrder()->getEmail() ?? '';

    $option = [
        'operation'    => 'Purchase',
        'merchant_id'  => $config['merchant_id'],
        'amount'       => $amount,
        'order_id'     => $order_id,
        'currency_iso' => $currency,
        'description'  => $description,
        'approve_url'  => $approve_url,
        'decline_url'  => $decline_url,
        'cancel_url'   => $cancel_url,
        'callback_url' => $callback_url,
        'language'     => $language,
        // Statistics.
        'client_last_name'  => $client_last_name,
        'client_first_name' => $client_first_name,
        'email' => $email,
        'phone' => '',
    ];

    $option['signature'] = $api->getRequestSignature($option);

    return $this->buildRedirectForm($form, $form_state, $api::URL, $option, $redirect_method);
  }

}
