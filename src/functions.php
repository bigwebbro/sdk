<?php

declare(strict_types=1);

if (!\function_exists('Tiyn\MerchantApiSdk\symfony_serializer_version')) {
    function symfony_serializer_version(): int
    {
        if ($version = \Composer\InstalledVersions::getVersion('symfony/serializer')) {
            $version = substr($version, 0, 1);

            return (int) $version;
        }

        return 0;
    }
}
