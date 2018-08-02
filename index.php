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
session_start();
if($_SESSION['user_type_id']==5)
  include_once('adminnav.php');
include_once('config.php');
$start_hour=$_REQUEST['start_hour'];
if($start_hour==null)
  $start_hour="10";
$start_min=$_REQUEST['start_min'];
if($start_min==null)
  $start_min="30";
$end_hour=$_REQUEST['end_hour'];
if($end_hour==null)
  $end_hour="10";
$end_min=$_REQUEST['end_min'];
if($end_min==null)
  $end_min="30";
?>
<HTML>
<HEAD>
<TITLE>Add Meeting Information</TITLE>
<link href="css/main.css" rel="stylesheet" type="text/css"></LINK>
<link type="text/css" rel="stylesheet" href="css/popupcalendar.css" media="screen"></LINK>
<SCRIPT type="text/javascript" src="js/popupcalendar.js"></script>
<SCRIPT type="text/javascript" src="js/helper.js"></script>
<script type="text/javascript" src="js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
<style type="text/css">
.small{
  width: 80%;
  }

</style>
</HEAD>
<BODY>
<center>
    <?php echo "<font color=\"red\"><b>".$_REQUEST['message']."</b></font>";?>
    <h2> Enter Meeting Information </h2>

     <form name="localform" action="addingmeetings.php" method="post" enctype="multipart/form-data">
     <fieldset class=small>
	   <legend align="center">Enter Program Information</legend>
	     <table width="85%">
	        <tr>
	           <th align="left" width="30%">Meeting Name :</th>
	           <td><input type="text" name="meeting_name" value="<?php echo $_REQUEST['meeting_name'] ?>" size="70"><sup><font color="red">*</font></sup>
	        </tr>
	        <tr>
	           <th align="left" width="30%">Short Description :</th>
	           <td><textarea name="short_description" rows="5" cols="53"><?php echo $_REQUEST['short_description'] ?></textarea>
	        </tr>
	        <tr>
	           <th align="left">Organization Type :</th>
	           <td><select name="organization_type_id">
	           <?php
	                    $sql="select * from organization_types order by organization_type";
	                    $result=mysql_query($sql);
	                    while($row=mysql_fetch_assoc($result))
	                          {
	                           ?>
	                            <option value="<?php echo $row['organization_type_id'];?>" <?php if($row['organization_type_id']==$_REQUEST['organization_type_id']){ echo "selected";}?>><?php echo $row['organization_type'];?></option>
	                           <?php
	                          }
	                         ?>
	                  </select> <sup><font color="red">*</font></sup>
	        </tr>
	        <tr>
	           <th align="left"> Meeting Start Date and Time:</th>
	           <td><input id="start_date" onClick="displayCalendar(document.localform.start_date ,'mm-dd-yy',this);"  onkeypress="return false;" name="start_date"  value="<?php echo $_REQUEST['start_date'] ?>" size="10"><sup><font color="red">*</font></sup>
	               &nbsp; at <select name="start_hour"><sup>
	                    <?php for ($x=0;$x<=23;$x++)
	                          {
	                           if($x<10)
	                             $x="0".$x;
	                           ?>
	                            <option value="<?php echo $x;?>" <?php if($x==$start_hour){ echo "selected";}?>><?php echo $x;?></option>
	                           <?php
	                          }
	                         ?>
	                  </select> &nbsp; hrs<sup><font color="red">*</font></sup>
	                : <select name="start_min">
	                    <?php for ($x=0;$x<=59;$x++)
	                          {
	                           if($x<10)
	                             $x="0".$x;
	                           ?>
	                            <option value="<?php echo $x;?>" <?php if($x==$start_min){ echo "selected";}?>><?php echo $x;?></option>
	                           <?php
	                          }
	                         ?>
	                  </select> &nbsp; mins<sup><font color="red">*</font></sup>
	        </tr>
	        <tr>
	           <th align="left"> Meeting End Date and Time:</th>
	           <td><input id="end_date" onClick="displayCalendar(document.localform.end_date ,'mm-dd-yy',this);"  onkeypress="return false;" name="end_date"  value="<?php echo $_REQUEST['end_date'] ?>" size="10"><sup><font color="red">*</font></sup>
	               &nbsp; at <select name="end_hour"><sup>
	                    <?php for ($x=0;$x<=23;$x++)
	                          {
	                           if($x<10)
	                             $x="0".$x;
	                           ?>
	                            <option value="<?php echo $x;?>" <?php if($x==$end_hour){ echo "selected";}?>><?php echo $x;?></option>
	                           <?php
	                          }
	                         ?>
	                  </select> &nbsp; hrs<sup><font color="red">*</font></sup>
	                : <select name="end_min">
	                    <?php for ($x=0;$x<=59;$x++)
	                          {
	                           if($x<10)
	                             $x="0".$x;
	                           ?>
	                            <option value="<?php echo $x;?>" <?php if($x==$end_min){ echo "selected";}?>><?php echo $x;?></option>
	                           <?php
	                          }
	                         ?>
	                  </select> &nbsp; mins<sup><font color="red">*</font></sup>
	        </tr>
	        <tr>
	           <th align="left">Program frequency:</th>
	           <td align="left"><input  type="checkbox"  name="flag_daily" value="Y" <?php if($_REQUEST['flag_daily']=='Y') echo "checked";?>  />Daily &nbsp;&nbsp;
	                            <input  type="checkbox"  name="flag_weekly" value="Y" <?php if($_REQUEST['flag_weekly']=='Y') echo "checked";?>  />Weekly &nbsp;&nbsp;
	                            If weekly, which day<br>
	                            <input  type="radio"  name="flag_day" value="Sunday"    <?php if($_REQUEST['flag_day']=='Sunday')    echo "checked";?>  />Sunday &nbsp;&nbsp;
	                            <input  type="radio"  name="flag_day" value="Monday"    <?php if($_REQUEST['flag_day']=='Monday')    echo "checked";?>  />Monday &nbsp;&nbsp;
	                            <input  type="radio"  name="flag_day" value="Tuesday"   <?php if($_REQUEST['flag_day']=='Tuesday')   echo "checked";?>  />Tuesday &nbsp;&nbsp;
	                            <input  type="radio"  name="flag_day" value="Wednesday" <?php if($_REQUEST['flag_day']=='Wednesday') echo "checked";?>  />Wednesday &nbsp;&nbsp;
	                            <input  type="radio"  name="flag_day" value="Thursday"  <?php if($_REQUEST['flag_day']=='Thursday')  echo "checked";?>  />Thursday &nbsp;&nbsp;
	                            <input  type="radio"  name="flag_day" value="Friday"    <?php if($_REQUEST['flag_day']=='Friday')    echo "checked";?>  />Friday &nbsp;&nbsp;
	                            <input  type="radio"  name="flag_day" value="Saturday"  <?php if($_REQUEST['flag_day']=='Saturday')  echo "checked";?>  />Saturday
                </td>
            </tr>

	        <tr>
	           <th align="left">Time Zone :</th>
	           <td><select name="timezone">
	                 <option value="Eastern Standard Time"  <?php if("Eastern Standard Time"==$_REQUEST["time_zone"]) { echo "selected";}?>>Eastern Standard Time</option>
	                 <option value="Central Standard Time"  <?php if("Central Standard Time"==$_REQUEST["time_zone"]) { echo "selected";}?>>Central Standard Time</option>
	                 <option value="Mountain Standard Time" <?php if("Mountain Standard Time"==$_REQUEST["time_zone"]){ echo "selected";}?>>Mountain Standard Time</option>
	                 <option value="Pacific Standard Time"  <?php if("Pacific Standard Time"==$_REQUEST["time_zone"]) { echo "selected";}?>>Pacific Standard Time</option>
	                 <option value="Alaska Standard Time"   <?php if("Alaska Standard Time"==$_REQUEST["time_zone"])  { echo "selected";}?>>Alaska Standard Time</option>
	                 <option value="Hawaii Standard Time"   <?php if("Hawaii Standard Time"==$_REQUEST["time_zone"])  { echo "selected";}?>>Hawaii Standard Time</option>
                     <option value="Local Time"             <?php if("Local Standard Time"==$_REQUEST["time_zone"])   { echo "selected";}?>>Local Standard Time</option>
	               </select><sup><font color="red">*</font></sup>
	        </tr>

         </table>
      </fieldset>
      <fieldset class=small>
	   <legend align="center">Enter Program Location</legend>
	     <table width="85%">
	        <tr>
	           <th align="left" width="30%">Street Address 1 :</th>
	           <td><input type="text" id="street_address_1" name="street_address_1" value="<?php echo $_REQUEST['street_address_1']; ?>" size="70"><sup><font color="red">*</font></sup>
	        </tr>
	        <tr>
	           <th align="left">Street Address 2 :</th>
	           <td><input type="text" name="street_address_2" value="<?php echo $_REQUEST['street_address_2']; ?>" size="70">
	        </tr>

            <tr>
               <th align="left">City :</th>
               <td><input  type="text" id="city" name="city" value="<?php echo $_REQUEST['city']; ?>" size="70" /></th><sup><font color="red">*</font></sup>
            </tr>
            <tr>
                <th align="left">State :</th>
 	            <td><select id="state" name="state">
 	                   <option value="">Select State</option>
                       <?php
                         $sql="select * from states order by name";
                         $result=mysql_query($sql);
                         while($row=mysql_fetch_assoc($result))
                           {
                            ?>
                              <option value="<?php echo $row['label'];?>" <?php if($row['label']==$_REQUEST["state"]) { echo "selected";}?> ><?php echo $row['name'];?></option>
                            <?php

                           }
                           ?>
 	                </select><sup><font color="red">*</font></sup>
             <tr>
           <tr>
               <th align="left">Zip Code :</th>
               <td><input  type="text" id="zip_code" name="zip_code" value="<?php echo $_REQUEST['zip_code']; ?>" size="70" /></th><sup><font color="red">*</font></sup>
            </tr>
            <tr>
		        <th align="left">Phone Number:</font></td>
		        <td><input type="text" size="3" name="areacode" value="<?php echo $_REQUEST['areacode'];?>"  maxlength="3" onKeyUp="return autoTab(this, 3, event);">
			        <input type="text" size="3" name="prefix"   value="<?php echo $_REQUEST['prefix'];?>" maxlength="3" onKeyUp="return autoTab(this, 3, event);">
		            <input type="text" size="3" name="number"   value="<?php echo $_REQUEST['number'];?>"maxlength="4" onKeyUp="return autoTab(this, 4, event);"> &nbsp; Ext:
		            <input type="text" size="3" name="ext"      value="<?php echo $_REQUEST['ext'];?>"maxlength="5" onKeyUp="return autoTab(this, 5, event);">
		        </td>
	        </tr>
            <tr>
               <th align="left">Capacity :</th>
               <td><select name="capacity">
                        <option value="0">Select Capacity</option>
	                    <?php for ($x=1;$x<=1000;$x++)
	                          {
	                           ?>
	                            <option value="<?php echo $x;?>" <?php if($x==$_REQUEST['capacity']){ echo "selected";}?>><?php echo $x;?></option>
	                           <?php
	                          }
	                         ?>
	                  </select>
            </tr>
	        <tr>
	           <th align="left">Upload Photo of Location:</th>
               <td><input type="file" name="theFile" size="70" value="<?php echo $_REQUEST['theFile'];?>">
            </tr>

         </table>
      </fieldset>
      <fieldset class=small>
	   <legend align="center">Enter Coordinator Information</legend>
	     <table width="85%">
           <tr>
               <th align="left" width="30%">Full Name :</th>
               <td><input  type="text"  name="contact_person_name" value="<?php echo $_REQUEST['contact_person_name']; ?>" size="70" /></th>
            </tr>
           <tr>
               <th align="left">Email :</th>
               <td><input  type="text"  name="contact_person_email" value="<?php echo $_REQUEST['contact_person_email']; ?>" onChange="validateemail();" size="70" /></th>
            </tr>
            <tr>
		        <th align="left">Phone Number:</font></td>
		        <td><input type="text" size="3" name="cp_areacode" value="<?php echo $_REQUEST['cp_areacode'];?>"  maxlength="3" onKeyUp="return autoTab(this, 3, event);">
			        <input type="text" size="3" name="cp_prefix"   value="<?php echo $_REQUEST['cp_prefix'];?>" maxlength="3" onKeyUp="return autoTab(this, 3, event);">
		            <input type="text" size="4" name="cp_number"   value="<?php echo $_REQUEST['cp_number'];?>"maxlength="4" onKeyUp="return autoTab(this, 4, event);"> &nbsp; Ext:
		            <input type="text" size="5" name="cp_ext"      value="<?php echo $_REQUEST['cp_ext'];?>"maxlength="5" onKeyUp="return autoTab(this, 5, event);">
		        </td>
	        </tr>
         </table>
      </fieldset>
      <fieldset class=small>
	   <legend align="center">Check All that Applies</legend>
	     <table width="85%">

            <tr>
               <th align="left"><input  type="checkbox"  name="flag_men" value="Y" <?php if($_REQUEST['flag_men']=='Y') echo "checked";?>  />Men Only &nbsp;&nbsp;
               <input  type="checkbox"  name="flag_women" value="Y" <?php if($_REQUEST['flag_women']=='Y') echo "checked";?>  />Women Only&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_youth" value="Y" <?php if($_REQUEST['flag_youth']=='Y') echo "checked";?>  />Youth&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_gay" value="Y" <?php if($_REQUEST['flag_gay']=='Y') echo "checked";?>  />Gay&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_wheelchair" value="Y" <?php if($_REQUEST['flag_wheelchair']=='Y') echo "checked";?>  />Wheel Chair Accessible&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_smoking" value="Y" <?php if($_REQUEST['flag_smoking']=='Y') echo "checked";?>  />Smoking Allowed&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_closed" value="Y" <?php if($_REQUEST['flag_closed']=='Y') echo "checked";?>  />Closed&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_open" value="Y" <?php if($_REQUEST['flag_open']=='Y') echo "checked";?>  />Open&nbsp;&nbsp;
               <input  type="checkbox"  name="flag_spanish" value="Y" <?php if($_REQUEST['flag_open']=='Y') echo "checked";?>  />Spanish</th>
            </tr>
         </table>
      </fieldset>
        <p>
        <input type="submit" value="Add this Meeting">
        </p>
      </form>


 </center>
 </BODY>
 </HTML>


 <script type="text/javascript">
 	var options = {
 		script:"autofillcitystate.php?json=true&limit=400&",
 		varname:"input",
 		json:true,
 		shownoresults:false,
 		maxresults:400,
 		timeout:25000,
 		delay:10,
 		callback: function (obj)
 		{
 		  document.getElementById('state').value = obj.id;
 		  document.getElementById('city').value = obj.display;
 		  if((document.getElementById('street_address_1').value!=null)&&
 		     (document.getElementById('city').value!=null)&&
 		     (document.getElementById('state').value!=null))
 		       {
 		       var address=document.getElementById('street_address_1').value+","+
 		                   document.getElementById('city').value+","+
 		                   document.getElementById('state').value;
               findzipcode(address);
               }

 		}
 	};
 	var as_json = new bsn.AutoSuggest('city', options);
</script>

