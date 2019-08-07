<?php
namespace Eddy\DbDump;

use Illuminate\Console\Command;

class DbDumpCommand extends Command
{
    protected $signature = 'dump:db';

    protected $description = 'Dump the database as an SQL file.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->alert('testing');
    }
}
