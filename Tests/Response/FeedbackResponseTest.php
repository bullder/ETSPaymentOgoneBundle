<?php

namespace ETS\Payment\OgoneBundle\Tests\Response;

use ETS\Payment\OgoneBundle\Response\FeedbackResponse;

class FeedbackResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        \BadMethodCallException
     * @expectedExceptionMessage already set
     */
    public function testAddValueFieldAlreadySet()
    {
        $feedbackResponse = new FeedbackResponse(array(
            'orderID'  => 42,
            'amount'   => '42',
            'currency' => 'EUR',
            'PM'       => 'credit card',
            'STATUS'   => 5,
            'CARDNO'   => 4567123478941234,
            'PAYID'    => 43,
        ));

        $class = new \ReflectionClass($feedbackResponse);
        $addValueMethod = $class->getMethod('addValue');
        $addValueMethod->setAccessible(true);

        $addValueMethod->invokeArgs($feedbackResponse, array('ORDERID', 48));
    }

    /**
     * @expectedException        \OutOfRangeException
     * @expectedExceptionMessage was not sent with the Request
     */
    public function testGetValueUnsetField()
    {
        $feedbackResponse = new FeedbackResponse(array(
            'orderID'  => 42,
            'amount'   => '42',
            'currency' => 'EUR',
            'PM'       => 'credit card',
            'STATUS'   => 5,
            'CARDNO'   => 4567123478941234,
            'PAYID'    => 43,
        ));

        $class = new \ReflectionClass($feedbackResponse);
        $getValueMethod = $class->getMethod('getValue');
        $getValueMethod->setAccessible(true);

        $getValueMethod->invokeArgs($feedbackResponse, array('dummyField'));
    }

    public function testSetValuesFromRequest()
    {
        $request = $this->getMock('Symfony\Component\HttpFoundation\Request', array('get'));
        $request->expects($this->any())
             ->method('get')
             ->will($this->returnValue('foo'));

        $feedbackResponse = new FeedbackResponse();
        $feedbackResponse->setValuesFromRequest($request);

        $this->assertSame(
            array(
                'ORDERID'    => 'foo',
                'AMOUNT'     => 'foo',
                'CURRENCY'   => 'foo',
                'PM'         => 'foo',
                'ACCEPTANCE' => 'foo',
                'STATUS'     => 'foo',
                'CARDNO'     => 'foo',
                'PAYID'      => 'foo',
                'NCERROR'    => 'foo',
                'BRAND'      => 'foo',
            ),
            $feedbackResponse->getValues()
        );
        $this->assertSame('foo', $feedbackResponse->getHash());
    }
}
