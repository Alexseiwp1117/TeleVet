<?php
namespace Test\Integration\Error;

require_once dirname(dirname(__DIR__)) . '/Setup.php';

use Test\Setup;
use Braintree;

class ValidationErrorCollectionTest extends Setup
{
    public function mapValidationErrorsToCodes($validationErrors)
    {
        $codes = array_map(create_function('$validationError', 'return $validationError->code;'), $validationErrors);
        sort($codes);
        return $codes;
    }

    public function test_shallowAll_givesAllErrorsShallowly()
    {
        $result = Braintree\Customer::create(array(
            'email' => 'invalid',
            'creditCard' => array(
                'number' => '1234123412341234',
                'expirationDate' => 'invalid',
                'billingAddress' => array(
                    'countryName' => 'invalid'
                )
            )
        ));

        $this->assertEquals(array(), $result->errors->shallowAll());

        $expectedCustomerErrors = array(Braintree\Error\Codes::CUSTOMER_EMAIL_IS_INVALID);
        $actualCustomerErrors = $result->errors->forKey('customer')->shallowAll();
        $this->assertEquals($expectedCustomerErrors, self::mapValidationErrorsToCodes($actualCustomerErrors));

        $expectedCreditCardErrors = array(
            Braintree\Error\Codes::CREDIT_CARD_EXPIRATION_DATE_IS_INVALID,
            Braintree\Error\Codes::CREDIT_CARD_NUMBER_IS_INVALID,
        );
        $actualCreditCardErrors = $result->errors->forKey('customer')->forKey('creditCard')->shallowAll();
        $this->assertEquals($expectedCreditCardErrors, self::mapValidationErrorsToCodes($actualCreditCardErrors));
    }

    public function test_deepAll_givesAllErrorsDeeply()
    {
        $result = Braintree\Customer::create(array(
            'email' => 'invalid',
            'creditCard' => array(
                'number' => '1234123412341234',
                'expirationDate' => 'invalid',
                'billingAddress' => array(
                    'countryName' => 'invalid'
                )
            )
        ));

        $expectedErrors = array(
            Braintree\Error\Codes::CUSTOMER_EMAIL_IS_INVALID,
            Braintree\Error\Codes::CREDIT_CARD_EXPIRATION_DATE_IS_INVALID,
            Braintree\Error\Codes::CREDIT_CARD_NUMBER_IS_INVALID,
            Braintree\Error\Codes::ADDRESS_COUNTRY_NAME_IS_NOT_ACCEPTED,
        );
        $actualErrors = $result->errors->deepAll();
        $this->assertEquals($expectedErrors, self::mapValidationErrorsToCodes($actualErrors));

        $expectedErrors = array(
            Braintree\Error\Codes::CREDIT_CARD_EXPIRATION_DATE_IS_INVALID,
            Braintree\Error\Codes::CREDIT_CARD_NUMBER_IS_INVALID,
            Braintree\Error\Codes::ADDRESS_COUNTRY_NAME_IS_NOT_ACCEPTED,
        );
        $actualErrors = $result->errors->forKey('customer')->forKey('creditCard')->deepAll();
        $this->assertEquals($expectedErrors, self::mapValidationErrorsToCodes($actualErrors));
    }
}
