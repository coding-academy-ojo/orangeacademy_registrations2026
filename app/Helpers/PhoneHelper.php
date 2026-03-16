<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Normalize phone number to +9627XXXXXXXX format.
     *
     * @param string|null $phone
     * @return string|null
     */
    public static function normalize($phone)
    {
        if (!$phone) {
            return null;
        }

        // Remove all non-numeric characters
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // Case: 009627XXXXXXXX
        if (str_starts_with($digits, '00962')) {
            $digits = substr($digits, 2);
        }

        // Case: 07XXXXXXXX (local format)
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // Case: 9627XXXXXXXX
        if (str_starts_with($digits, '962')) {
            $digits = substr($digits, 3);
        }

        // Now we should have 7XXXXXXXX or just junk
        if (strlen($digits) === 9 && str_starts_with($digits, '7')) {
            return '+962' . $digits;
        }

        // Return as is if it doesn't fit the expected pattern, validation will catch it
        // But if it already has +962, handle it
        if (str_starts_with($phone, '+962') && strlen($phone) === 13) {
            return $phone;
        }

        return $phone;
    }
}
