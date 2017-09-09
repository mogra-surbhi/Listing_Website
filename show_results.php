<?php
include("database.php");
$database="Nurse_Listing";
$latitude=$_GET['lat'];
$longitude=$_GET['lng'];
$filter=$_GET['filter'];
 $connection=mysql_connect ($servername, $username, $password);
if (!$connection) {
  die("Not connected : " . mysql_error());
}

$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ("Can\'t use db : " . mysql_error());
}
 

$radius = 100;


$query = sprintf("SELECT  address, lat, lng, ( 6371 * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM Location HAVING distance < '%s' ORDER BY distance LIMIT 0 , 20",
  mysql_real_escape_string($latitude),
  mysql_real_escape_string($longitude),
  mysql_real_escape_string($latitude),
  mysql_real_escape_string($radius));
$result = mysql_query($query);

$mysql_data=array();

if (!$result) {
  die("Invalid query: " . mysql_error());
}
$k=0;
$j=0;
$flag=0;
$e_id=array('ab');
if(mysql_num_rows($result)>0){
while($row = @mysql_fetch_assoc($result)){
   
    if($filter=='All')
   $sql="select email_id,name,rate from nurse where Residence='".$row['address']."' or work_address='".$row['address']."'";
    else
    {
        $sql="select email_id,name,rate from nurse where cater_to='".$filter."' and (Residence='".$row['address']."' or work_address='".$row['address']."')";
    }
        $result1 = $conn->query($sql);
        if($result1->num_rows>0)
        {
           while($row1 = $result1->fetch_assoc()){
               foreach($e_id as $m)
               {
                   if($m==$row1['email_id'])
                   {
                       $flag=1;
                   }
               }
               if($flag==0)
               {
                   $sql="select * from nurse_profile where email_id='".$row1['email_id']."'";
                   $result_nurse_profile=$conn->query($sql);
                   if($result_nurse_profile->num_rows>0){
                       $row_nurse_profile=$result_nurse_profile->fetch_assoc();
                       if($row_nurse_profile['picture']=='1'){
                       $image="upload/".$row1['email_id'].".png";
                       $quote=$row_nurse_profile['quote'];
                       }
                       else{
                            $image="upload/no-image.png";
                            $quote=$row_nurse_profile['quote'];
                       }
                   }
                   else{
                       $image='upload/no-image.png';
                       $quote="";
                   }
                array_push($mysql_data,array('lat'=>$row['lat'],'lng'=>$row['lng'],'name'=>$row1['name'],'email_id'=>$row1['email_id'],'rate'=>$row1['rate'],'address'=>$row['address'],'image'=>$image,'quote'=>$quote,'distance'=>round($row['distance'])));
                
                $e_id[$k]=$row1['email_id'];
                $k++;
               }
               $flag=0;
           }
        }
    }
    echo json_encode($mysql_data);
}
else 
{
    echo "No vendors available within range of 100 kilometers";
}
     ?>
 