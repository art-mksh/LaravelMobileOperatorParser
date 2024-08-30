<?php

namespace App\Console\Commands;

use ArtMksh\PhoneOperatorInfo\PhoneOperatorInfoService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use ArtMksh\OperatorPhoneInfo\OperatorPhoneInfo;

class UpdateOperatorPhoneInfo extends Command
{
    protected $signature = 'phoneinfo:update';
    protected $description = 'Update phone info from Rossvyaz API';

    public function handle()
    {
        $oPhoneOperatorInfoService = new PhoneOperatorInfoService();
        // Логика для получения данных из API и обновления базы данных
        $response = $oPhoneOperatorInfoService->fetchAllPhoneOperatorsData();

        if ($response->successful()) {
            foreach ($response->json()['data'] as $currentCode) {
                PhoneInfo::updateOrCreate(
                    ['code' => $currentCode['code']],
                    [
                        'operator' => $currentCode['operator'],
                        'region' => $currentCode['region']
                    ]
                );
            }
            $this->info('Phone info updated successfully.');
        } else {
            $this->error('Failed to update phone info.');
        }
        /*
        // Логика для получения данных из API и обновления базы данных
        $response = Http::get('https://api.rossvyaz.gov.ru/phoneinfo'); // Замените на реальный URL API

        if ($response->successful()) {
            foreach ($response->json() as $data) {
                PhoneInfo::updateOrCreate(
                    ['number' => $data['number']],
                    ['operator' => $data['operator'], 'region' => $data['region']]
                );
            }
            $this->info('Phone info updated successfully.');
        } else {
            $this->error('Failed to update phone info.');
        }
        */
    }
}