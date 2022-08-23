<?php


namespace App\Support;

use \Illuminate\Database\Migrations\Migration as Base;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use PDO;


class Migration extends Base
{
    private function connection()
    {
        $connection = $this->connection ?? null;
        return DB::connection($connection);
    }

    protected function jsonColumnType()
    {
        $pdo = $this->connection()->getPdo();
        $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $server_version = $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
        return ($driver === 'pgsql' && version_compare($server_version, '9.2', 'ge'))
        || ($driver === 'mysql' && version_compare($server_version, '5.7.8', 'ge'))
            ? 'json' : 'text';
    }

    public function jsonColumn(Blueprint $table, $columnName)
    {
        return $table->{$this->jsonColumnType()}($columnName);
    }
}
