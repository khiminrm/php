<?php
/**
 * Purchase model for the Signifyd SDK
 *
 * PHP version 5.6
 *
 * @category  Signifyd_Fraud_Protection
 * @package   Signifyd\Core
 * @author    Signifyd <info@signifyd.com>
 * @copyright 2018 SIGNIFYD Inc. All rights reserved.
 * @license   See LICENSE.txt for license details.
 * @link      https://www.signifyd.com/
 */
namespace Signifyd\Models;

use Signifyd\Core\Model;
use Signifyd\Models\Product;
use Signifyd\Models\Shipment;
use Signifyd\Models\DiscountCode;

/**
 * Class Purchase
 * Data related to purchase event represented in this Case
 * Creation request.
 *
 * @category Signifyd_Fraud_Protection
 * @package  Signifyd\Core
 * @author   Signifyd <info@signifyd.com>
 * @license  See LICENSE.txt for license details.
 * @link     https://www.signifyd.com/
 */
class Purchase extends Model
{
    /**
     * The unique ID for the user's browsing session. This is
     * to be used in conjunction with the Signifyd Fingerprinting
     * Javascript.
     *
     * @var string
     */
    public $orderSessionId;

    /**
     * The IP Address of the browser that was used to make the
     * purchase. This is the IP Address that was used to connect
     * to your site and make the purchase.
     *
     * @var string
     */
    public $browserIpAddress;

    /**
     * A string uniquely identifying this order.
     *
     * @var string
     */
    public $orderId;

    /**
     * The date and time when the order was placed, shown on the
     * signifyd console. Format yyyy-MM-dd'T'HH:mm:ssZ
     *
     * @var string
     */
    public $createdAt;

    /**
     * The gateway that processed the transaction.
     *
     * @var string
     */
    public $paymentGateway;

    /**
     * The method the user used to complete the purchase.
     *
     * @var string
     */
    public $paymentMethod;

    /**
     * The currency type of the order, in 3 letter ISO 4217 format.
     *
     * @var string
     */
    public $currency = 'USD';

    /**
     * The response code from the address verification system (AVS).
     *
     * @var string
     */
    public $avsResponseCode;

    /**
     * The response code from the card verification value (CVV) check.
     *
     * @var string
     */
    public $cvvResponseCode;

    /**
     * The unique identifier provided by the payment gateway
     * for this order.
     *
     * @var string
     */
    public $transactionId;

    /**
     * The method used by the buyer to place the order.
     *
     * @var string
     */
    public $orderChannel;

    /**
     * If the order was was taken by a customer service or sales
     * agent, his or her name
     *
     * @var string
     */
    public $receivedBy;

    /**
     * The total price of the order, including shipping price and taxes.
     *
     * @var float
     */
    public $totalPrice;

    /**
     * The products purchased in the transaction.
     *
     * @var array $products Array of Product objects
     */
    public $products = [];

    /**
     * The shipments associated with this purchase.
     *
     * @var array $shipments Array of Shipment objects
     */
    public $shipments = [];

    /**
     * Any discount codes, coupons, or promotional codes used
     * during checkout to receive a discount on the order.
     * You can only provide the discount code and the discount
     * amount OR the discount percentage.
     *
     * @var array $discountCodes Array of DiscountCode objects
     */
    public $discountCodes = [];

    /**
     * The class attributes
     *
     * @var array $fields The list of class fields
     */
    protected $fields = [
        'orderSessionId',
        'browserIpAddress',
        'orderId',
        'createdAt',
        'paymentGateway',
        'paymentMethod',
        'currency',
        'avsResponseCode',
        'cvvResponseCode',
        'transactionId',
        'orderChannel',
        'receivedBy',
        'totalPrice',
        'products',
        'shipments',
        'discountCodes'
    ];
    /**
     * The validation rules
     *
     * @var array $fieldsValidation List of rules
     */
    protected $fieldsValidation = [
        'orderSessionId' => [],
        'browserIpAddress' => ['required'],
        'orderId' => ['required'],
        'createdAt' => ['required', 'datetime'],
        'paymentGateway' => ['required'],
        'paymentMethod' => ['required'],
        'currency' => ['required'],
        'avsResponseCode' => ['required'],
        'cvvResponseCode' => ['required'],
        'transactionId' => ['required'],
        'orderChannel' => ['required'],
        'receivedBy' => ['required'],
        'totalPrice' => ['required', 'double'],
    ];

    protected $objectFields = [
        'products',
        'shipments',
        'discountCodes'
    ];

    /**
     * Purchase constructor.
     *
     * @param array $data The purchase data
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                if (!in_array($field, $this->fields)) {
                    continue;
                }

                $this->{'set' . ucfirst($field)}($value);
            }

            if (isset($data['products']) && is_array($data['products'])) {
                foreach ($data['products'] as $item) {
                    $product = new Product($item);
                    $this->addProduct($product);
                }
            }

            if (isset($data['shipments']) && is_array($data['shipments'])) {
                foreach ($data['shipments'] as $sItem) {
                    $shipment = new Shipment($sItem);
                    $this->addShipment($shipment);
                }
            }

            if (isset($data['discountCodes']) && is_array($data['discountCodes'])) {
                foreach ($data['discountCodes'] as $dItem) {
                    $discountCode = new DiscountCode($dItem);
                    $this->addDiscountCode($discountCode);
                }
            }
        }
    }

    /**
     * Validate the purchase
     *
     * @return bool
     */
    public function validate()
    {
        //        if (is_array($purchase)) {
        //
        //            return true;
        //        } elseif (is_object($purchase)) {
        //            return true;
        //        } else {
        //            return false;
        //        }
        //TODO add code to validate the purchase
        return true;
    }

    /**
     * Add product item to the products array
     *
     * @param \Signifyd\Models\Product $product Product Item
     *
     * @return void
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
    }

    /**
     * Add shipment item to the shipments array
     *
     * @param \Signifyd\Models\Shipment $shipment Shipment Item
     *
     * @return void
     */
    public function addShipment($shipment)
    {
        $this->shipments[] = $shipment;
    }

    /**
     * Add the discount code item to the discount code array
     *
     * @param \Signifyd\Models\DiscountCode $discountCode Discount Item
     *
     * @return void
     */
    public function addDiscountCode($discountCode)
    {
        $this->discountCodes[] = $discountCode;
    }

    /**
     * Get the order session id
     *
     * @return mixed
     */
    public function getOrderSessionId()
    {
        return $this->orderSessionId;
    }

    /**
     * Set the order session id
     *
     * @param mixed $orderSessionId The session id
     *
     * @return void
     */
    public function setOrderSessionId($orderSessionId)
    {
        $this->orderSessionId = $orderSessionId;
    }

    /**
     * Get the browser ip address
     *
     * @return mixed
     */
    public function getBrowserIpAddress()
    {
        return $this->browserIpAddress;
    }

    /**
     * Set the browser ip address
     *
     * @param mixed $browserIpAddress The ip address
     *
     * @return void
     */
    public function setBrowserIpAddress($browserIpAddress)
    {
        $this->browserIpAddress = $browserIpAddress;
    }

    /**
     * Get the order creation date
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the order creation date
     *
     * @param mixed $createdAt The create date
     *
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the order id
     *
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set the order id
     *
     * @param mixed $orderId The order id
     *
     * @return void
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Get the payment gateway
     *
     * @return mixed
     */
    public function getPaymentGateway()
    {
        return $this->paymentGateway;
    }

    /**
     * Set the payment gateway
     *
     * @param mixed $paymentGateway The name of payment gateway
     *
     * @return void
     */
    public function setPaymentGateway($paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Get the payment method
     *
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set the payment method
     *
     * @param mixed $paymentMethod The payment method name
     *
     * @return void
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Get the currency
     *
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the order currency
     *
     * @param mixed $currency Currency code
     *
     * @return void
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Get the AVS code
     *
     * @return mixed
     */
    public function getAvsResponseCode()
    {
        return $this->avsResponseCode;
    }

    /**
     * Set the AVS code
     *
     * @param mixed $avsResponseCode Code received from gateway
     *
     * @return void
     */
    public function setAvsResponseCode($avsResponseCode)
    {
        $this->avsResponseCode = $avsResponseCode;
    }

    /**
     * Get the CVV code
     *
     * @return mixed
     */
    public function getCvvResponseCode()
    {
        return $this->cvvResponseCode;
    }

    /**
     * Set the CVV code
     *
     * @param mixed $cvvResponseCode Code received from gateway
     *
     * @return void
     */
    public function setCvvResponseCode($cvvResponseCode)
    {
        $this->cvvResponseCode = $cvvResponseCode;
    }

    /**
     * Get the transaction id
     *
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set the transaction id
     *
     * @param mixed $transactionId Id received from gateway
     *
     * @return void
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * Get the order channel
     *
     * @return mixed
     */
    public function getOrderChannel()
    {
        return $this->orderChannel;
    }

    /**
     * Set the order channel
     *
     * @param mixed $orderChannel The order channel
     *
     * @return void
     */
    public function setOrderChannel($orderChannel)
    {
        $this->orderChannel = $orderChannel;
    }

    /**
     * Get received by
     *
     * @return mixed
     */
    public function getReceivedBy()
    {
        return $this->receivedBy;
    }

    /**
     * Set received by
     *
     * @param mixed $receivedBy Who took the order
     *
     * @return void
     */
    public function setReceivedBy($receivedBy)
    {
        $this->receivedBy = $receivedBy;
    }

    /**
     * Get total price
     *
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set the total price of the order
     *
     * @param mixed $totalPrice The total value of order
     *
     * @return void
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * Get a list of products
     *
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set a list of products
     *
     * @param array $products Array of products
     *
     * @return void
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * Get a list of shipments
     *
     * @return array
     */
    public function getShipments()
    {
        return $this->shipments;
    }

    /**
     * Set a list of shipments
     *
     * @param array $shipments Array of shipments
     *
     * @return void
     */
    public function setShipments($shipments)
    {
        $this->shipments = $shipments;
    }

    /**
     * Get a list of discount codes
     *
     * @return array
     */
    public function getDiscountCodes()
    {
        return $this->discountCodes;
    }

    /**
     * Set a list of discount codes
     *
     * @param array $discountCodes Array of discount codes
     *
     * @return void
     */
    public function setDiscountCodes($discountCodes)
    {
        $this->discountCodes = $discountCodes;
    }

}
