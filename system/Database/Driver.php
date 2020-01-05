<?php

namespace Dea\Database;

interface Driver
{
    public function quoteIdent($s);

    public function tableExists($table);
    public function tableStatus($table);
    public function tableSchema($name, $refresh);

    public function createTable($table);
    public function adjustTable($table, $schema);
    public function dropTable($table);

    public function emptyDatabase();

    // function snapshot($filename, $tbls);
    // function restore($filename, &$restore_filename, $tables);

    public function diagnose();
}