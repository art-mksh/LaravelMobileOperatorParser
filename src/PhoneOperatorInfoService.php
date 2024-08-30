<?php

declare(strict_types=1);

namespace ArtMksh\PhoneOperatorInfo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

//use App\Models\PhoneOperatorInfo;
use ArtMksh\PhoneOperatorInfo\Models\PhoneOperatorInfo;

class PhoneOperatorInfoService
{
    //protected $apiUrl = 'https://api.rossvyaz.gov.ru';
    protected $apiUrl = 'https://opendata.digital.gov.ru/api/v1/abcdef';
//https://opendata.digital.gov.ru/api/v1/abcdef/phone?num=9199167011&limit=50
//https://opendata.digital.gov.ru/api/v1/abcdef?export=json
    public function getPhoneInfo($phoneNumber)
    {
        // Сначала проверяем локальную базу данных
        $info = PhoneOperatorInfo::where('number', $phoneNumber)->first();

        if ($info) {
            return $info; // Возвращаем данные из локальной базы
        }

        // Если данных нет в локальной базе, обращаемся к удаленному API
        return Cache::remember("phone_info_{$phoneNumber}", 60, function () use ($phoneNumber) {
            return $this->fetchPhoneInfo($phoneNumber);
        });
    }

    protected function fetchPhoneInfo($phoneNumber, $limit = 50)
    {
        //$methodUrl = "{$this->apiUrl}?export=json";
        //$response = Http::get("{$this->apiUrl}/search?num={$phoneNumber}");
        $response = Http::get("{$this->apiUrl}/phone?num={$phoneNumber}&limit={$phoneNumber}");

        if ($response->successful()) {
            // Сохраняем данные в локальную базу данных
            PhoneOperatorInfo::updateOrCreate(
                [
                    'code' => $phoneNumber
                ],
                [
                    'operator' => $response->json('operator'),
                    'region' => $response->json('region'),
                ]
            );

            return [
                'code' => $phoneNumber,
                'operator' => $response->json('operator'),
                'region' => $response->json('region'),
            ];
        }

        return null; // Обработка ошибок
    }

    public function fetchAllPhoneOperatorsData()
    {
        //$response = Http::get("{$this->apiUrl}/phone?num={$phoneNumber}&limit={$phoneNumber}");
        //$methodUrl = "{$this->apiUrl}?export=json";
        $response = Http::get("{$this->apiUrl}?export=json");
        if ($response->successful()) {
            return $response;
        }
        return null;
    }

    public function updateAllPhoneOperatorsDataCommand(): bool
    {
        $oPhoneOperatorInfoService = new PhoneOperatorInfoService();
        $response = $oPhoneOperatorInfoService->fetchAllPhoneOperatorsData();
        $responseJsoned = $response->json();

        if ($response->successful() && !empty($responseJsoned['data']) && count($responseJsoned['data']) > 0) {
            foreach ($responseJsoned['data'] as $currentCode) {
                var_dump($currentCode);
                break;
                PhoneOperatorInfo::updateOrCreate(
                    [
                        'code' => $currentCode['code']
                    ],
                    [
                        'operator' => $currentCode['operator'],
                        'region' => $currentCode['region']
                    ]
                );
            }
            return true;
        } else {
            return false;
        }
    }
}