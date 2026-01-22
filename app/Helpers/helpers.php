<?php

use Carbon\Carbon;

if (!function_exists('tanggalIndo')) {
    function tanggalIndo($tanggal, $withTime = true)
    {
        if (!$tanggal) return '-';

        $format = $withTime ? 'd F Y H:i' : 'd F Y';

        return Carbon::parse($tanggal)
            ->locale('id')
            ->translatedFormat($format);
    }
}
