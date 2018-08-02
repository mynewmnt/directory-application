/**
 * @package     12 Step Meetings & Groups Directory Application
 * @author      Myrin New (mnew@mynewtechnologies.com)
 * @copyright   (C) 2005 - 2018 MyNew Technologies LLC. All rights reserved.
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0-standalone.html   
 * @link        https://mynewtechnologies.com/clients
 * 
 * Based on design concepts of Myrin New 2005!
 */

<?php
include_once('config.php');
/*************************************************************************************
               Capture all parameters recieved and send back if required
**************************************************************************************/
$paramspassed="";
foreach ($_REQUEST as $key => $value)
  $paramspassed=$paramspassed."&".$key."=".urlencode($value);
/***********************************************************************************
               Check all Required Fields were passed
***********************************************************************************/
if ((strlen($_REQUEST['miles']==0))||(strlen($_REQUEST['zip_code']==0)))
{
header("Location: search.php?message=Miles and Zipcode are both Required".$paramspassed);
exit;
}
$addon="";
if (!empty($_REQUEST['organization_type_id']))
   $addon.=" and b.organization_type_id=".$_REQUEST['organization_type_id'];
if (!empty($_REQUEST['city']))
   $addon.=" and b.city='".$_REQUEST['city']."'";
if (!empty($_REQUEST['state']))
   $addon.=" and b.state='".$_REQUEST['state']."'";
if ($_REQUEST['flag_men']=='Y')
   $addon.=" and b.flag_men='Y'";
if ($_REQUEST['flag_women']=='Y')
   $addon.=" and b.flag_women='Y'";
if ($_REQUEST['flag_youth']=='Y')
   $addon.=" and b.flag_youth='Y'";
if ($_REQUEST['flag_gay']=='Y')
   $addon.=" and b.flag_gay='Y'";
if ($_REQUEST['flag_wheelchair']=='Y')
   $addon.=" and b.flag_wheelchair='Y'";
if ($_REQUEST['flag_smoking']=='Y')
   $addon.=" and b.flag_smoking='Y'";
if ($_REQUEST['flag_closed']=='Y')
   $addon.=" and b.flag_closed='Y'";
if ($_REQUEST['flag_open']=='Y')
   $addon.=" and b.flag_open='Y'";
if ($_REQUEST['flag_spanish']=='Y')
   $addon.=" and b.flag_spanish='Y'";
/***********************************************************************************

***********************************************************************************/
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAvMPBJgAIom50G_n8vR1GSBSfc3U0MECizeo7N-0SegXRW2l5MxRYrGjgU-d4dwoWp38nsJdd-oBIiQ" type="text/javascript"></script>



<?php
$sql="select * from zipcodes where zipcode=".$_REQUEST['zip_code'];
$result=mysql_query($sql);
if($row=mysql_fetch_assoc($result))
  {
   $centerlatitude=$row['latitude'];
   $centerlongitude=$row['longitude'];
  }



$sql=" SELECT *, round( degrees( acos( sin( radians( b.latitude ) ) * sin( radians( a.latitude ) ) + cos( radians( b.latitude ) ) * cos( radians( a.latitude ) ) * cos( radians( b.longitude - a.longitude ) ) ) ) *60 * 1.1515 ,2) as distance ".
     " FROM zipcodes AS a, meetings AS b ,organization_types c".
     " WHERE b.organization_type_id = c.organization_type_id ".
     " AND a.zipcode='".$_REQUEST['zip_code']. "'". $addon.
     " AND round( degrees( acos( sin( radians( b.latitude ) ) * sin( radians( a.latitude ) ) + cos( radians( b.latitude ) ) * cos( radians( a.latitude ) ) * cos( radians( b.longitude - a.longitude ) ) ) ) *60 * 1.1515 ) <= ".$_REQUEST['miles'].
     " order by distance ";
$result=mysql_query($sql);
?>
<script>
function initialize(zoom)
{
 if (GBrowserIsCompatible())
    {

     var map = new GMap2(document.getElementById("map_canvas"));
     map.setCenter(new GLatLng(<?php echo $centerlatitude;?>,<?php echo $centerlongitude;?>), zoom);
     map.setUIToDefault();
<?php
$loop=0;
while($row=mysql_fetch_assoc($result))
    {
     $loop++;
     $duration="From ".
               substr($row['start_date_time'],8,2).'/'.
               substr($row['start_date_time'],5,2)."/".
               substr($row['start_date_time'],0,4).
               " to ".
               substr($row['end_date_time'],8,2).'/'.
               substr($row['end_date_time'],5,2)."/".
               substr($row['end_date_time'],0,4).
               " between ".
               substr($row['start_date_time'],10,6).
               " - ".
               substr($row['end_date_time'],10,6).
               " ".
               $row['timezone'];
      $phone="(".substr($row['location_phone'],0,3).") ".substr($row['location_phone'],3,3)."-".substr($row['location_phone'],6,4) ;
      if($row[location_phone_ext]!=null)
         $phone.=' Ext - '.$row['location_phone_ext'];
     ?>
     var latlng<?php echo $loop;?> = new GLatLng(<?php echo $row['latitude'];?>,<?php echo $row['longitude'];?>);
     var marker<?php echo $loop;?>  = new GMarker(latlng<?php echo $loop;?> );
     GEvent.addListener(marker<?php echo $loop;?>, "mouseover", function()
             {
              <?php
              $html="\"<b><i>".$row['meeting_name']."</i><b><br><b><font color=red>".$row['organization_type']."</font></b><br> ".$duration."<br>".$row['street_address_1']."<br>".$row['city'].", ".$row['state'].", ".$row['zip_code']."<br>Phone : ".$phone. "<br><b>".$row['distance']." miles from zipcode ".$_REQUEST['zip_code']."</b>\"";
              ?>
              marker<?php echo $loop;?>.openInfoWindowHtml(<?php echo $html;?> );
             });
         map.addOverlay(marker<?php echo $loop;?>);
    <?php
    }
?>

    }

}

</script>
<?php

$result=mysql_query($sql);
echo mysql_num_rows ($result)." Locations found <br>";
$x=0;
while($row=mysql_fetch_assoc($result))
{
echo $row['meeting_name']."->".$row['distance']."<br>";
}
?>
</HEAD>
<BODY onLoad="initialize(10)" onUnload="GUnload()">
<center>
<div id="map_canvas" style="width: 800px; height: 600px"></div>







