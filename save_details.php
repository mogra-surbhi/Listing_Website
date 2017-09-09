<?php
        include ("database.php");
        $page=$_POST['page'];
        if($page=='register'){
            $category=$_POST['category'];
            $fname=$_POST['fname'];
            $lname=$_POST['lname'];
            $mobile=$_POST['mobile'];
            $city=$_POST['city'];
            $add1=$_POST['address1'];
            $add2=$_POST['address2'];
            $residence=$add1.$city;
            $city=$_POST['city'];
            $bday=$_POST['dob'];
            $gender=$_POST['gender'];
            $stmt = $conn->prepare("INSERT INTO vendor_personal(category, fname, lname, contact_no, dob, gender, home_address1, home_address2, city) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssss",$category,$fname,$lname,$mobile,$bday,$gender,$add1,$add2,$city);
            $stmt->execute();
            $sql="select vendor_id from vendor_personal where contact_no='".$mobile."'";
            $result = $conn->query($sql);
            $row= $result->fetch_assoc();
            $vendor_id=$row['vendor_id'];
            session_start();
            $_SESSION['vendor_id']=$vendor_id;
            $email=$_POST['email'];
            $password=$_POST['password'];
            $stmt = $conn->prepare("INSERT INTO login(vendor_id,email_id,password) VALUES (?,?,?)");
            $stmt->bind_param("iss",$vendor_id,$email,$password);
            $stmt->execute();
            function getLatLong($residence){
                   if(!empty($residence)){
                    //Formatted address
                    $formattedAddr = str_replace(' ','+',$residence);
                   //Send request and receive json data by address
                   $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false'); 
                   $output = json_decode($geocodeFromAddr);
                   //Get latitude and longitute from json data
                   $data['latitude']  = $output->results[0]->geometry->location->lat; 
                   $data['longitude'] = $output->results[0]->geometry->location->lng;
                   //Return latitude and longitude of the given address
                   if(!empty($data)){
                       return $data;
                   }else{
                      return false;
                   }
          }
          else{
             return false;   
          }
    }  
    $latLong = getLatLong($residence);
    if($latLong!=false)
    {
    $type="home";
    $latitude = $latLong['latitude']?$latLong['latitude']:'Not found';
    $longitude = $latLong['longitude']?$latLong['longitude']:'Not found';
    $stmt = $conn->prepare("INSERT INTO address_lat_lng(vendor_id,type,lat,lng) VALUES (?,?,?,?)");
    $stmt->bind_param("isdd",$vendor_id,$type,$latitude,$longitude);
    $stmt->execute();
    }
 }
            
?>
