<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\GiftMessage\Model;

class ObserverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\GiftMessage\Model\Observer
     */
    protected $model;

    protected function setUp()
    {
        $giftMessageFactoryMock = $this->getMock('\Magento\GiftMessage\Model\MessageFactory', [], [], '', false);
        $giftMessageMock = $this->getMock('\Magento\GiftMessage\Helper\Message', [], [], '', false);
        $this->model = new \Magento\GiftMessage\Model\Observer($giftMessageFactoryMock, $giftMessageMock);
    }

    public function testMultishippingEventCreateOrders()
    {
        $giftMessageId = 42;
        $observerMock = $this->getMock('\Magento\Framework\Event\Observer');
        $eventMock = $this->getMock('\Magento\Framework\Event', ['getOrder', 'getAddress']);
        $addressMock = $this->getMock('\Magento\Quote\Model\Quote\Address', ['getGiftMessageId'], [], '', false);
        $orderMock = $this->getMock('\Magento\Sales\Model\Order', ['setGiftMessageId'], [], '', false);
        $observerMock->expects($this->exactly(2))->method('getEvent')->willReturn($eventMock);
        $eventMock->expects($this->once())->method('getAddress')->willReturn($addressMock);
        $addressMock->expects($this->once())->method('getGiftMessageId')->willReturn($giftMessageId);
        $eventMock->expects($this->once())->method('getOrder')->willReturn($orderMock);
        $orderMock->expects($this->once())->method('setGiftMessageId')->with($giftMessageId);
        $this->assertEquals($this->model, $this->model->multishippingEventCreateOrders($observerMock));
    }

    public function testSalesEventQuoteSubmitBefore()
    {
        $giftMessageId = 42;
        $observerMock = $this->getMock('\Magento\Framework\Event\Observer');
        $eventMock = $this->getMock('\Magento\Framework\Event', ['getOrder', 'getQuote']);
        $quoteMock = $this->getMock('\Magento\Quote\Model\Quote', ['getGiftMessageId'], [], '', false);
        $orderMock = $this->getMock('\Magento\Sales\Model\Order', ['setGiftMessageId'], [], '', false);
        $observerMock->expects($this->exactly(2))->method('getEvent')->willReturn($eventMock);
        $eventMock->expects($this->once())->method('getQuote')->willReturn($quoteMock);
        $quoteMock->expects($this->once())->method('getGiftMessageId')->willReturn($giftMessageId);
        $eventMock->expects($this->once())->method('getOrder')->willReturn($orderMock);
        $orderMock->expects($this->once())->method('setGiftMessageId')->with($giftMessageId);
        $this->assertEquals($this->model, $this->model->salesEventQuoteSubmitBefore($observerMock));
    }
}
