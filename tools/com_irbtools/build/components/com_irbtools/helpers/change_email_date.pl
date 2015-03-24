#!/usr/bin/perl
#+########################################################################
#
#
# File: ldapsearch_uid.pl
#
# Date: 22-Apr-09
#
# Author: Francisco Jose Lozano Alemany (ITS IRB).
#
#
# Description: Performs a search in the irbbarcelona ldap server. Optioanlly,
#              it can replace attributes of those matching entries.
#

use Data::Types qw(:all);
use Net::LDAP;
use Net::LDAP::Search;
use Net::LDAP::Message;
require "getopts.pl";   # Subroutines: Getopts.



#define variables
  $DEBUG=0;
  $verbose = 0;
  $replace = 0;
  $admin_bind = 0;

# parse the command line.

  ($program_name) = ($0 =~ /([^\/]+)$/);   # Strip off the path to the command.
   print "Program name: $program_name\n" if $DEBUG;
   &Usage if !(&Getopts ('varh'));
   &Usage if defined($opt_h);
   if (!defined $ARGV[1]) {
      &Usage();
      exit (0);
   } 
   $user = $ARGV[0]; print "User: $user\n" if $DEBUG;
   $date = $ARGV[1]; print "Date: $date\n" if $DEBUG;

#Now start with main program

if (defined($opt_r)){$replace=1;}
if (defined($opt_v)){$verbose=1;}
if (defined($opt_a)){$admin_bind=1;}


$ldap = Net::LDAP->new( 'irbsvr4.irb.pcb.ub.es' ) or die "$@";

if ($admin_bind) {
    $mesg = $ldap->bind( 'cn=admin, o=irbbarcelona',
                          password => 'irbbarcelona'
                       );
    print ("Admin bind\n") if $DEBUG;
} else {
    $mesg = $ldap->bind ;    # an anonymous bind
    print ("Anonymous bind\n") if $DEBUG;
}

#$filter = "(cn=$user)";
$filter = "(mail=$user)";

$mesg = $ldap->search( # perform a search
                        base   => "o=irbbarcelona",
                        filter => $filter
                      );

$mesg->code && die $mesg->error;


   my $max = $mesg->count;
   my $new_date = $date;


   if ($max) {
      for ( $i = 0 ; $i < $max ; $i++ ) {
         my $entry = $mesg->entry ( $i );
         print $entry->get_value("cn")." - ".
               $entry->get_value("fullname").
			   "\nEmail: ".
               $entry->get_value("mail").
			   "\nOld IRB-FechaBajaMail: ".
               $entry->get_value("IRB-FechaBajaMail")."\n" if $verbose;
          if ($replace) {
             print "New IRB-FechaBajaMail: $new_date \n" if $verbose; 
             $entry->replace ( 'IRB-FechaBajaMail' => $new_date ); 
# 2013-4-05 Roberto. Actualiza también el valor de IRB-EMail para evitar el bug de borrado cuando se modifica una fecha.             
             $entry->replace ( 'IRB-EMail' => 'TRUE' ); 
          }


#         print $entry->dn($dn)."\n";
#         foreach my $attr ( $entry->attributes ) {
#           print join( "\n ", $attr, $entry->get_value( $attr ) ), "\n";
#          }

          $entry->update ( $ldap ); # update directory server
       }
       print ("User $user exists\n") if $verbose;
   } else {
       print ("User $user DOES NOT exist\n") if $verbose;
   }

#   print $mesg->count."\n";


# Subroutine: Usage
# Action: Print the way to use the current command
#
sub Usage {
   print "Usage: $program_name [-v][-h][-a][-r] <username> <date>\n";
   print "Options: [-v] Verbose\n";
   print "         [-h] help. Shows this usage.\n";
   print "         [-a] admin bind. Binds the LDAP server as the admin user.\n";
   print "         [-r] replace\n";
   exit -1;
}

sub print_verbose {
    print $_ if $verbose;
}
