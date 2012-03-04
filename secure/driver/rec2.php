<?


$car=$_GET[car]; $elem=$_GET[elem];
if ($car != 0){
$dbc=mysql_connect('localhost','root','earth') or die(mysql_error());
mysql_select_db('planet_reservations');
$q=mysql_query("select car_log.*, codes.code from car_log, codes where car_log.vehicleid='$car' and car_log.driverid=codes.id order by car_log.startTime desc limit 1");
$r=mysql_fetch_assoc($q);

}else{
$r[mileage]=''; $r[cash]='';$r[$tires]='df=40dr=40pf=40pr=40';
     }
$feedback="$elem".'|';
 if ($r[endTime]==0 && $car!=0){
$nowTime=date(U);$outTime=$r[startTime];
$outTime=round(($nowTime-$outTime)/60);$msgTime='';
$hours=0;$mins=0;$mins=$outTime;
while($mins>60){$mins-=60;$hours++;}
if($hours>1){$msgTime="$hours hours, ";}
$msgTime.="$mins minutes ago";
$feedback.="<font color=red><b>$r[code] checked out this car $msgTime. </b></font><br>";
                    }

$feedback.='Mileage:<input type="text" name="mileage" value="';
$feedback.="$r[mileage]";
$feedback.='" size="6" maxlength="6" id="mileage">
Cash: <input type="text" name="cash" value="';
$feedback.="$r[cash]";
$feedback.='" size="3" maxlength="3" id="cash"><br>
Tires:<input type="text" name="tires"  value="';
$feedback.="$r[tires]";
$feedback.='" size="20" maxlength="20" id="tires"><br>';


echo"$feedback";


?>
