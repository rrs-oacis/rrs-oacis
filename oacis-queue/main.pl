#!/usr/bin/env perl
use strict;
use warnings;

#-------------------------------------------------------------------------------
# export PERL_MM_USE_DEFAULT=1
# cpan -i DBI
# cpan -i DBD::SQLite
#-------------------------------------------------------------------------------
use File::Basename;
use DBI;

chdir(dirname($0));

my $script_name = $ARGV[0];
my $output = $ARGV[1];

if (!$script_name)
{
    die('ERROR: script name is unspecified as the 1st argument.');
}

if (!$output)
{
    $output = '/tmp/oq.log';
}

&main($script_name, $output);
sub main
{
    my ($script_name, $output) = @_;
    my $database_name = 'queue.db';
    my $lock_name = 'queue.lock';

    my $dbi = &db_create_and_connect($database_name);
    &db_queue($dbi, $script_name);

    if (!&worker_lock($lock_name))
    {
        &db_disconnect($dbi);
        exit(0);
    }

    while (1)
    {
        my $script_name = &db_peek($dbi);
        if (!$script_name)
        {
            sleep(1);
            next;
        }

        &worker_execute($script_name, $output);
        &db_delete($dbi, $script_name);
    }

    &db_disconnect($dbi);
    &worker_unlock($lock_name);
}

sub db_create_and_connect
{
    my $database_name = shift(@_);
    my $dbi = DBI->connect("dbi:SQLite:dbname=$database_name");
    my $sth = $dbi->prepare(
        'CREATE TABLE IF NOT EXISTS
            queue (id INTEGER PRIMARY KEY, script_name)');
    $sth->execute();
    return $dbi;
}

sub db_disconnect
{
    my $dbi = shift(@_);
    $dbi->disconnect();
}

sub db_queue
{
    my ($dbi, $script_name) = @_;
    my $sth = $dbi->prepare('INSERT INTO queue (script_name) VALUES (?)');
    $sth->bind_param(1, $script_name);
    $sth->execute();
}

sub db_peek
{
    my $dbi = shift(@_);
    my $sth = $dbi->prepare('SELECT script_name FROM queue LIMIT 1');
    $sth->execute();
    my $row = $sth->fetchrow_hashref();
    return $row ? $row->{'script_name'} : '';
}

sub db_delete
{
    my ($dbi, $script_name) = @_;
    my $sth = $dbi->prepare('DELETE FROM queue WHERE script_name=?');
    $sth->bind_param(1, $script_name);
    $sth->execute();
}

sub worker_lock
{
    my $lock_name = shift(@_);
    return mkdir($lock_name);
}

sub worker_unlock
{
    my $lock_name = shift(@_);
    return rmdir($lock_name);
}

sub worker_execute
{
    my ($script_name, $output) = @_;
    system("echo >> $output");
    system("echo \\#$script_name $$ >> $output");

    system("chmod u+x scripts/$script_name");
    system('rm --force --recursive tmp && mkdir tmp');
    system("cd tmp && bash -l -c ../scripts/$script_name 2>&1 >> $output");
    system("rm --force scripts/$script_name");
}
