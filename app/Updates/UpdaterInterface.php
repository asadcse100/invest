<?php


namespace App\Updates;


interface UpdaterInterface
{
    public function getVersion();

    public function handle();
}
