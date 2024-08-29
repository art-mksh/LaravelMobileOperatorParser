<?php

declare(strict_types=1);

namespace ArtMksh\PhoneOperatorInfo;

use SQLite3;

class PhoneOperatorInfo
{
    protected $db;

    public function __construct()
    {
        $this->db = new SQLite3(__DIR__ . '/../storage/default.sqlite');
    }

    public function searchMobileOperator($phoneNumber)
    {
        if (preg_match('/^7\d{10}$/', $phoneNumber)) {
            $stmt = $this->db->prepare('SELECT * FROM PhoneOperatorInfo WHERE number = :number"');
            $stmt->bindValue(':number', $phoneNumber, SQLITE3_TEXT);
            $result = $stmt->execute();

            return $result->fetchArray(SQLITE3_ASSOC);
        }

        return null;
    }
}