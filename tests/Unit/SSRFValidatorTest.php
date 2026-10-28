<?php

namespace Tests\Unit;

use App\Domain\Monitoring\Services\SSRFValidator;
use Exception;
use PHPUnit\Framework\TestCase;

class SSRFValidatorTest extends TestCase
{
    public function test_it_blocks_loopback_ip(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('blocked');
        SSRFValidator::validate('http://127.0.0.1:8080/admin');
    }

    public function test_it_blocks_private_rfc1918_class_a(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('blocked');
        SSRFValidator::validate('http://10.0.1.5/status');
    }

    public function test_it_blocks_private_rfc1918_class_c(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('blocked');
        SSRFValidator::validate('http://192.168.1.1/router');
    }

    public function test_it_blocks_cloud_metadata_ip(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('blocked');
        SSRFValidator::validate('http://169.254.169.254/latest/meta-data/');
    }

    public function test_it_blocks_localhost_hostname(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('blocked');
        SSRFValidator::validate('http://localhost:3000');
    }

    public function test_it_blocks_forbidden_url_schemes(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('forbidden');
        SSRFValidator::validate('file:///etc/passwd');
    }
}
