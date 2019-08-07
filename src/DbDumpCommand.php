<?php
namespace Eddy\DbDump;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DbDumpCommand extends Command
{
    protected $signature = 'dump:db';

    protected $description = 'Dump the database as an SQL file.';

    private $config;

    private $fs;

    public function __construct(Config $config, Filesystem $fs)
    {
        $this->config = $config;
        $this->fs = $fs;
        parent::__construct();
    }

    public function handle()
    {
        if ('mysql' !== $this->config->get('database.default')) {
            $this->error('This command currently supports only MySQL');
            return;
        }

        $conn = $this->config->get('database.connections.mysql');

        $fn = $conn['database'] . '_' . Carbon::now()->getTimestamp() . '.sql';

        if (!$this->fs->exists('storage/backup')) {
            $this->info('Backup directory not found. Creating backup directory in storage/backup...');
            
            $this->fs->makeDirectory('storage/backup');
            
            $this->info('Adding .gitignore to storage/backup...');
            
            $this->fs->put('storage/backup/.gitignore', "*.sql\n");
        }

        $process = new Process(sprintf(
            'mysqldump -u %s -p%s %s > %s',
            $conn['username'],
            $conn['password'],
            $conn['database'],
            $path = 'storage/backup/' . $fn
        ));

        try {
            $process->mustRun();
            $this->info('Database exported successfully to '.$path);
        } catch (ProcessFailedException $e) {
            throw $e;
        }
    }
}
