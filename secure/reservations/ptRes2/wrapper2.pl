#/usr/local/bin/perl
BEGIN {push @INC, "/home/planet/scripts"};

use FareCalc;

my $distance = $ARGV[0];
my $market = $ARGV[1];

if (!$distance || !$market) {
	print "0";
	die;
}

my $airport = false;
if ($market eq "SFO" || $from =~ /logan/i || $to =~ /logan/i) {
	$airport = true;
}

my $fare = FareCalc::calcFare($distance, $market);
print $fare;
