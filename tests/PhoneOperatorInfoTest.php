<?php

declare(strict_types=1);

namespace ArtMksh\PhoneOperatorInfo\Tests;

use ArtMksh\PhoneOperatorInfo\PhoneOperatorInfo;
use PHPUnit\Framework\TestCase;

class PhoneOperatorInfoTest extends TestCase
{
    use RefreshDatabase;

    protected PhoneOperatorInfo $operatorInfoTest;
    protected function setUp(): void
    {
        $oOperatorInfoTest = new PhoneOperatorInfo();
    }

    public function testSuccessfulSearchMobileOperator()
    {
        $info = $this->operatorInfoTest->searchMobileOperator('79014300001');
        $this->assertNotNull($info);
        $this->assertEquals('ООО "Т2 Мобайл"', $info['operatorName']);
    }

    public function testUnsuccessfulSearchMobileOperator()
    {
        $info = $this->operatorInfoTest->searchMobileOperator('79014300000');
        $this->assertNull($info);
    }

    public function testGetPhoneInfoFromLocal()
    {
        // Создаем тестовые данные в локальной базе
        $phoneInfo = PhoneInfo::create([
            'number' => '79856660205',
            'operator' => 'ПАО "Мобильные ТелеСистемы"',
            'region' => 'Республика Татарстан',
        ]);

        $rossvyazApi = new RossvyazApi();
        $info = $rossvyazApi->getPhoneInfo('79856660205');

        $this->assertNotNull($info);
        $this->assertEquals('ПАО "Мобильные ТелеСистемы"', $info->operator);
        $this->assertEquals('Республика Татарстан', $info->region);
    }

    public function testGetPhoneInfoFromRemote()
    {
        $rossvyazApi = new RossvyazApi();

        // Имитация успешного ответа от API
        $this->mockApiResponse('79856660205', [
            'operator' => 'ПАО "Мобильные ТелеСистемы"',
            'region' => 'Республика Татарстан',
        ]);

        $info = $rossvyazApi->getPhoneInfo('79856660205');
        $this->assertNotNull($info);
        $this->assertEquals('ПАО "Мобильные ТелеСистемы"', $info['operator']);
        $this->assertEquals('Республика Татарстан', $info['region']);
    }

    protected function mockApiResponse($phoneNumber, $response)
    {
        Http::fake([
            "https://api.rossvyaz.gov.ru/search?num={$phoneNumber}" => Http::response($response, 200),
        ]);
    }
}