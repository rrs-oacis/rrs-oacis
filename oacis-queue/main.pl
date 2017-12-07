#!/usr/bin/perl
#
# export PERL_MM_USE_DEFAULT=1
# cpan -i DBI
# cpan -i DBD::SQLite
#
use File::Basename;
use DBI;

chdir dirname $0;

my $script = $ARGV[0];
my $enqueued = 0;

my $output = $ARGV[1];
if ($output eq "")
{
	$output = "/dev/null";
}

my $database = 'queue.db';
if ( ! -f $database )
{
	my $dbi = DBI->connect("dbi:SQLite:dbname=$database");
	$sth = $dbi->prepare('create table queue(id integer primary key, script);');
	$sth->execute(); $sth->finish();
	$dbi->disconnect();
}

my $dbi = DBI->connect("dbi:SQLite:dbname=$database");

if ( ! -d 'scripts' )
{
    mkdir 'scripts';
}

if ( -f 'scripts/'.$script && $script ne '')
{
    my $sth = $dbi->prepare('insert into queue(script) values(?);');
    $sth->bind_param(1, $script);
    $sth->execute();
    $sth->finish();
    $enqueued++;
}

my $sth = $dbi->prepare('select count(*) from queue;');
$sth->execute();
my $count = $sth->fetch()->[0];
$sth->finish();

if (0 < ($count - $enqueued))
{
    $dbi->disconnect();
    exit 0;
}

while ($count > 0)
{
    my $sth = $dbi->prepare('select script from queue limit 1;');
    $sth->execute();
    my $script = $sth->fetch()->[0];
    $sth->finish();

    system('echo >>"'.$output.'"');
    system('echo "#'.$script.'" >>"'.$output.'"');

    system('chmod a+x scripts/'.$script);
    system('rm -rf tmp');
    mkdir 'tmp';
    system('chown oacis:oacis tmp');
    chdir 'tmp';
    system('sudo -i -u oacis `dirname $PWD`/scripts/'.$script.' >>"'.$output.'" 2>&1');
    chdir '..';
    system('rm -rf tmp');
    system('rm -f scripts/'.$script);

    $sth = $dbi->prepare('delete from queue where script=?;');
    $sth->bind_param(1, $script);
    $sth->execute();
    $sth->finish();

    $sth = $dbi->prepare('select count(*) from queue;');
    $sth->execute();
    $count = $sth->fetch()->[0];
    $sth->finish();
}

$dbi->disconnect();
exit 0;
