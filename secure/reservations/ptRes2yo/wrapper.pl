#/usr/local/bin/perl
#BEGIN {push @INC, "/home/planet2/scripts"};
BEGIN {push @INC, "/home/planet/scripts"};

use FareCalc3;

my $zip = $ARGV[0];
my $loc2 = $ARGV[1];
my $search = $ARGV[2];

if (!$zip || !$loc2) {
	print "0";
	die;
}


my $distance = FareCalc3::dbDistance($zip, $loc2);

# Test whether distance is in DB. If not, and $search hasn't been passed in,
# die. Used to separate Saturn quotes from the rest.
if (!$distance && !$search) {
	print "0";
	die;
}

my $market = substr $zip, 0, 1;
$market = $market == 9 ? "SFO" : "BOS";
my $airport = false;
if ($market eq "SFO" || $from =~ /logan/i || $to =~ /logan/i) {
	$airport = true;
}

# FareCalc::getFare: from, to, discount (1), airport, market
# $discount is currently being done elsewhere in both calc_fares and qq.php
# $airport only determines whether there will be a $3 fee-- true/false test 
# $market gets passed to calcFare. Used to tell the diff between SF/bos rates 
# old method: my $fare = FareCalc::calcFare($distance, $market);

my $fare = FareCalc3::getFare($zip, $loc2, 1, $airport, $market);
print $fare;
