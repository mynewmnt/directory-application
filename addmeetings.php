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
if (empty($_REQUEST['meeting_name']))
{
header("Location: index.php?message=Meeting Name is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['organization_type_id']))
{
header("Location: index.php?message=Organization Name is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['start_date']))
{
header("Location: index.php?message=Start Date is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['start_hour']))
{
header("Location: index.php?message=Start Hour is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['start_min']))
{
header("Location: index.php?message=Start Min is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['end_date']))
{
header("Location: index.php?message=End Date is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['end_hour']))
{
header("Location: index.php?message=End Hour is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['end_min']))
{
header("Location: index.php?message=End Min is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['timezone']))
{
header("Location: index.php?message=Time Zone is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['street_address_1']))
{
header("Location: index.php?message=Street Address 1 is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['city']))
{
header("Location: index.php?message=City Address 1 is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['state']))
{
header("Location: index.php?message=State is Required".$paramspassed);
exit;
}
if (empty($_REQUEST['zip_code']))
{
header("Location: index.php?message=Zip Code is Required".$paramspassed);
exit;
}
/****************************************************************************
            Check and build dates to enter into database
****************************************************************************/
$start_date=$_REQUEST['start_date'];
$start_hour=$_REQUEST['start_hour'];
$start_min=$_REQUEST['start_min'];
$start_date_time=substr($start_date,6,4)."-".substr($start_date,0,2)."-".substr($start_date,3,2)." ".$start_hour.":".$start_min.":00";
$end_date=$_REQUEST['end_date'];
$end_hour=$_REQUEST['end_hour'];
$end_min=$_REQUEST['end_min'];
$end_date_time=substr($end_date,6,4)."-".substr($end_date,0,2)."-".substr($end_date,3,2)." ".$end_hour.":".$end_min.":00";
$sql="select  str_to_date('".$start_date_time."','%Y-%m-%d %T') > str_to_date('".$end_date_time."','%Y-%m-%d %T') as valid";
$result=mysql_query($sql);
$row=mysql_fetch_assoc($result);
if($row['valid']==1)
{
header("Location: index.php?message=Start Date/Time cannot be after End Date/Time".$paramspassed);
exit;
}
/***************************************************************
           Other Error Checks
***************************************************************/
if( (!empty($_REQUEST['flag_weekly']) )&&(empty($_REQUEST['flag_day'])))
{
header("Location: index.php?message=Day of the week has to be picked for weekly meetings".$paramspassed);
exit;
}

if( (!empty($_REQUEST['flag_weekly']) )&&(!empty($_REQUEST['flag_daily'])))
{
header("Location: index.php?message=Is this a daily or weekly activity".$paramspassed);
exit;
}
$flag_day=$_REQUEST['flag_day'];
if (!empty($_REQUEST['flag_daily']))
   $flag_day=null;
$location_phone=$_REQUEST['areacode'].$_REQUEST['prefix'].$_REQUEST['number'];
if ((!empty($location_phone)) &&(strlen($location_phone)<10))
{
header("Location: index.php?message=Location Phone does not have 10 characters".$paramspassed);
exit;
}
$contact_person_phone=$_REQUEST['cp_areacode'].$_REQUEST['cp_prefix'].$_REQUEST['cp_number'];
if ((!empty($contact_person_phone)) &&(strlen($contact_person_phone)<10))
{
header("Location: index.php?message=Contact Person Phone does not have 10 characters".$paramspassed);
exit;
}

/****************************************************************
   Get Latitude / Longitude
****************************************************************/
$lat_long_basis="Not Available";
$address=$_REQUEST['street_address_1'].",".$_REQUEST['city'].",".$_REQUEST['state'].",".$_REQUEST['zip'];
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAAzr2EBOXUKnm_jVnk0OJI7xSosDVG8KKPE1-m51RBrvYughuyMxQ-i1QfUnH94QxWIa6N4U6MouMmBA");
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
$request_url = $base_url . "&q=" . urlencode($address);
$xml = simplexml_load_file($request_url) or die("url not loading");
$status = $xml->Response->Status->code;
if (strcmp($status, "200") == 0)
{
      // Successful geocode
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $latitude = $coordinatesSplit[1];
      $longitude = $coordinatesSplit[0];
      $sa= $xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->SubAdministrativeArea->Locality->Thoroughfare->ThoroughfareName;

    // echo $sa."****";




      if(!empty($sa))
         $lat_long_basis="Actual";
 }
if($lat_long_basis=="Not Available")
{
 $sql="select * from zipcodes where zipcode='".$_REQUEST['zip_code']."'";
 $result=mysql_query($sql);
 if($row=mysql_fetch_assoc($result))
   {
      $latitude = $row['latitude'];
      $longitude = $row['longitude'];
      $lat_long_basis="Zip Code";
   }
}
/**********************************************************
         Process Flyer Attachment
**********************************************************/


if($_FILES['theFile']['tmp_name'] !=null)
{

$currentdir=getcwd();

$filename	= $_FILES['theFile']['name'];
$temp_name	= $_FILES['theFile']['tmp_name'];
$error		= $_FILES['theFile']['error'];
$filesize   = $_FILES['theFile']['size'];
$temp=$currentdir."/photos";
if(!file_exists($temp))
   mkdir($temp);
$converted_filename=time()."_".$filename;
$uploadfilepath="photos/".$converted_filename;
$uploadeddir=$temp."/".$converted_filename;
if(!$error)
copy($temp_name, $uploadeddir);
}

/******************************************************************
  Insert Into Database the row
******************************************************************/
$meeting_name=str_replace("'","''",$_REQUEST['meeting_name']);
$short_description=str_replace("'","''",$_REQUEST['short_description']);
$street_address_1=str_replace("'","''",$_REQUEST['street_address_1']);
$street_address_2=str_replace("'","''",$_REQUEST['street_address_2']);
$city=str_replace("'","''",$_REQUEST['city']);
$state=str_replace("'","''",$_REQUEST['state']);
$zip_code=str_replace("'","''",$_REQUEST['zip_code']);
$flag_daily=str_replace("'","''",$_REQUEST['flag_daily']);
if(empty($flag_daily))
  $flag_daily='N';
$flag_weekly=str_replace("'","''",$_REQUEST['flag_weekly']);
if(empty($flag_weekly))
  $flag_weekly='N';
$ext=str_replace("'","''",$_REQUEST['ext']);
$contact_person_name=str_replace("'","''",$_REQUEST['contact_person_name']);
$contact_person_email=str_replace("'","''",$_REQUEST['contact_person_email']);

$cp_ext=str_replace("'","''",$_REQUEST['cp_ext']);
$flag_closed=str_replace("'","''",$_REQUEST['flag_closed']);
if(empty($flag_closed))
  $flag_closed='N';
$flag_open=str_replace("'","''",$_REQUEST['flag_open']);
if(empty($flag_open))
  $flag_open='N';
$flag_men=str_replace("'","''",$_REQUEST['flag_men']);
if(empty($flag_men))
  $flag_men='N';
$flag_women=str_replace("'","''",$_REQUEST['flag_women']);
if(empty($flag_women))
  $flag_women='N';
$flag_youth=str_replace("'","''",$_REQUEST['flag_youth']);
if(empty($flag_youth))
  $flag_youth='N';
$flag_gay=str_replace("'","''",$_REQUEST['flag_gay']);
if(empty($flag_gay))
  $flag_gay='N';
$flag_wheelchair=str_replace("'","''",$_REQUEST['flag_wheelchair']);
if(empty($flag_wheelchair))
  $flag_wheelchair='N';
$flag_smoking=str_replace("'","''",$_REQUEST['flag_smoking']);
if(empty($flag_smoking))
  $flag_smoking='N';
$flag_spanish=str_replace("'","''",$_REQUEST['flag_spanish']);
if(empty($flag_spanish))
  $flag_spanish='N';

$sql="insert into meetings(meeting_name,short_description,organization_type_id,street_address_1,street_address_2,city,state,zip_code,".
     "start_date_time,end_date_time,timezone,latitude,longitude,meeting_capacity,flag_daily,flag_weekly,day_of_the_week,location_phone,location_phone_ext,".
     "contact_person_name,contact_person_email,contact_person_phone,contact_person_phone_ext,flag_closed,flag_open,flag_men,flag_women,flag_youth,flag_gay,".
     "flag_wheelchair,flag_smoking,flag_spanish,created_at,updated_at,photo_file_name,flag_approved,lat_long_basis) values(".
     "'".$meeting_name."',".
     "'".$short_description."',".
     $_REQUEST['organization_type_id'].",".
     "'".$street_address_1."',".
     "'".$street_address_2."',".
     "'".$city."',".
     "'".$state."',".
     "'".$zip_code."',".
     "'".$start_date_time."',".
     "'".$end_date_time."',".
     "'".$_REQUEST['timezone']."',".
     "'".$latitude."',".
     "'".$longitude."',".
     $_REQUEST['capacity'].",".
     "'".$flag_daily."',".
     "'".$flag_weekly."',".
     "'".$flag_day."',".
     "'".$location_phone."',".
     "'".$ext."',".
     "'".$contact_person_name."',".
     "'".$contact_person_email."',".
     "'".$contact_person_phone."',".
     "'".$cp_ext."',".
     "'".$flag_closed."',".
     "'".$flag_open."',".
     "'".$flag_men."',".
     "'".$flag_women."',".
     "'".$flag_youth."',".
     "'".$flag_gay."',".
     "'".$flag_wheelchair."',".
     "'".$flag_smoking."',".
     "'".$flag_spanish."',".
     "now(),now(),".
     "'".$uploadfilepath."',".
     "'N',".
     "'".$lat_long_basis."')";
     mysql_query($sql) or die("Failed<br>".$sql);
header("Location: index.php?message=Location was added successfully.".$paramspassed);
exit;
?>




