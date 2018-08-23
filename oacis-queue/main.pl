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
	$output = "/tmp/oq.log";
}

my $database = 'queue.db';
if ( ! -f $database )
{
	my $dbi = &getDBI();
	$sth = $dbi->prepare('create table queue(id integer primary key, script);');
	$sth->execute(); $sth->finish();
	$dbi->disconnect();
	releaseDB();
}

my $dbi = &getDBI();

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
$dbi->disconnect();
releaseDB();


if ( -f 'queue.pid')
{
	my $pid = 0 + `cat queue.pid`;
	if (0 == system("kill -0 ".$pid))
	{
		print '[working]'.$pid."\n";
		exit 0;
	}
}
system("echo ".$$." > queue.pid");
sleep(1);


print '[remaining]'.$count."\n";
while ($count > 0)
{
	$dbi = &getDBI();
	my $sth = $dbi->prepare('select script from queue limit 1;');
	$sth->execute();
	my $script = $sth->fetch()->[0];
	$sth->finish();
	$dbi->disconnect();
	releaseDB();

	system('echo >>"'.$output.'"');
	system('echo "#'.$script.' '.$$.'" >>"'.$output.'"');
	print '#'.$script."\n";

	system('chmod a+x scripts/'.$script);
	system('rm -rf tmp');
	mkdir 'tmp';
	system('chown oacis:oacis tmp');
	chdir 'tmp';
	system('bash -l -c ../scripts/'.$script.' >>"'.$output.'" 2>&1');
	chdir '..';
	system('rm -rf tmp');
	system('rm -f scripts/'.$script);

	$dbi = &getDBI();
	$sth = $dbi->prepare('delete from queue where script=?;');
	$sth->bind_param(1, $script);
	$sth->execute();
	$sth->finish();

	$sth = $dbi->prepare('select count(*) from queue;');
	$sth->execute();
	$count = $sth->fetch()->[0];
	$sth->finish();
	$dbi->disconnect();
	releaseDB();

	print '[remaining]'.$count."\n";
}

#$dbi->disconnect();
#system("rm -f queue.pid");

exit 0;


sub getDBI()
{
    while (-f 'db.lock') { select undef, undef, undef, 0.1; }
    system("echo ".$$." > db.lock");
    $dbi = DBI->connect("dbi:SQLite:dbname=$database");
}
sub releaseDB()
{
    system("rm -f db.lock");
}
