<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Helpers\PhoneHelper;

class PhoneHelperTest extends TestCase
{
    public function test_normalization_formats()
    {
        $this->assertEquals('+962776806141', PhoneHelper::normalize('0776806141'));
        $this->assertEquals('+962791234567', PhoneHelper::normalize('00962791234567'));
        $this->assertEquals('+962781234567', PhoneHelper::normalize('962781234567'));
        $this->assertEquals('+962771234567', PhoneHelper::normalize('771234567'));
        $this->assertEquals('+962771234567', PhoneHelper::normalize('+962771234567'));
        $this->assertEquals('+962776806141', PhoneHelper::normalize(' 077 680 6141 '));
    }

    public function test_invalid_formats()
    {
        $this->assertEquals('12345', PhoneHelper::normalize('12345'));
        $this->assertEquals(null, PhoneHelper::normalize(null));
    }
}
