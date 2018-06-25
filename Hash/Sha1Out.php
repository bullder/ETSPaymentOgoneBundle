<?php

namespace ETS\Payment\OgoneBundle\Hash;

use ETS\Payment\OgoneBundle\Client\TokenInterface;

/*
 * Copyright 2014 ETSGlobal <ecs@etsglobal.org>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Sha-1 Out generator.
 *
 * @author ETSGlobal <ecs@etsglobal.org>
 */
class Sha1Out implements GeneratorInterface
{
    protected static $acceptableFields = [
        'AAVADDRESS',
        'AAVCHECK',
        'AAVMAIL',
        'AAVNAME',
        'AAVPHONE',
        'AAVZIP',
        'ACCEPTANCE',
        'ALIAS',
        'AMOUNT',
        'BIC',
        'BIN',
        'BRAND',
        'CARDNO',
        'CCCTY',
        'CN',
        'COLLECTOR_BIC',
        'COLLECTOR_IBAN',
        'COMPLUS',
        'CREATION_STATUS',
        'CREDITDEBIT',
        'CURRENCY',
        'CVCCHECK',
        'DCC_COMMPERCENTAGE',
        'DCC_CONVAMOUNT',
        'DCC_CONVCCY',
        'DCC_EXCHRATE',
        'DCC_EXCHRATESOURCE',
        'DCC_EXCHRATETS',
        'DCC_INDICATOR',
        'DCC_MARGINPERCENTAGE',
        'DCC_VALIDHOURS',
        'DEVICEID',
        'DIGESTCARDNO',
        'ECI',
        'ED',
        'EMAIL',
        'ENCCARDNO',
        'FXAMOUNT',
        'FXCURRENCY',
        'IP',
        'IPCTY',
        'MANDATEID',
        'MOBILEMODE',
        'NBREMAILUSAGE',
        'NBRIPUSAGE',
        'NBRIPUSAGE_ALLTX',
        'NBRUSAGE',
        'NCERROR',
        'ORDERID',
        'PAYID',
        'PAYMENT_REFERENCE',
        'PM',
        'SCO_CATEGORY',
        'SCORING',
        'SEQUENCETYPE',
        'SIGNDATE',
        'STATUS',
        'SUBBRAND',
        'SUBSCRIPTION_ID',
        'TRXDATE',
        'VC',
    ];

    /**
     * @var TokenInterface
     */
    protected $token;

    /**
     * @param TokenInterface $token
     */
    public function __construct(TokenInterface $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public static function isAcceptableField($field)
    {
        return in_array(strtoupper($field), self::$acceptableFields);
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function generate(array $parameters)
    {
        $stringToHash = $this->getStringToHash($parameters);

        return strtoupper(sha1($stringToHash));
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    protected function getStringToHash(array $parameters)
    {
        $stringToHash = '';
        $parameters = array_change_key_case($parameters, CASE_UPPER);

        foreach (self::$acceptableFields as $acceptableField) {
            if (isset($parameters[strtoupper($acceptableField)]) && '' !== (string) $parameters[strtoupper($acceptableField)]) {
                $stringToHash .= sprintf('%s=%s%s', strtoupper($acceptableField), $parameters[strtoupper($acceptableField)], $this->token->getShaout());
            }
        }

        return $stringToHash;
    }
}
