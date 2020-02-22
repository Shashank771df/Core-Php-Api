<?php
include "connect.php";
$response = array();
    if(isset($_GET['apicall'])){
        switch($_GET['apicall']){
            case 'signup':
                if(isTheseParametersAvailable(array('username','email_id','mobile_no','location','profile_image','password'))){
                $username = $_POST['username'];
                $email_id = $_POST['email_id'];
                $mobile_no = $_POST['mobile_no'];
                $location = $_POST['location'];
                $profile_image = $_POST['profile_image'];
                $password = md5($_POST['password']);


                $stmt = $obj->conn->prepare("SELECT id FROM end_users WHERE email_id = ?");
                $stmt->bind_param("s",$email_id);
                $stmt->execute();
                $stmt->store_result();

                if($stmt->num_rows > 0){
                    $response['error'] = true;
                    $response['message'] = 'User already registered';
                    $stmt->close();
                }else{
                    $stmt = $obj->conn->prepare("INSERT INTO end_users (username, email_id, mobile_no, location, profile_image, password) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $username, $email_id, $mobile_no, $location, $profile_image, $password);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT  username, email_id, mobile_no, location, profile_image, password FROM end_users WHERE username = ?");
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $stmt->bind_result($username, $email_id, $mobile_no, $location, $profile_image, $password);
                        $stmt->fetch();

                        $user = array(
                            'username'=>$username,
                            'email_id'=>$email_id,
                            'mobile_no'=>$mobile_no,
                            'location'=>$location,
                            'profile_image'=>$profile_image,
                            'password'=>$password
                        );

                        $stmt->close();
                        
                        $response['error'] = false;
                        $response['status'] = 1;
                        $response['message'] = 'User registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required parameters are not available';
            }
            break;
            
            case 'doctor_signup':
                if(isTheseParametersAvailable(array('doctor_name','doctor_password','doctor_location','doctor_icon','doctor_mail','doctor_start_timing','doctor_end_timing','working_days','consult_fee','status','gender'))){
                $username = $_POST['doctor_name'];
                $password = $_POST['doctor_password'];
                $location = $_POST['doctor_location'];
                $icon = $_POST['doctor_icon'];
                $mail = $_POST['doctor_mail'];
                $stime = $_POST['doctor_start_timing'];
                $etime = $_POST['doctor_end_timing'];
                $working_days = $_POST['working_days'];
                $fee = $_POST['consult_fee'];
                $status = $_POST['status'];
                $gender = $_POST['gender'];


                 $stmt = $obj->conn->prepare("SELECT * FROM doctor WHERE doctor_mail = ?");
                 //$stmt->bind_param("ss", $username, $mail);
                 $stmt->bind_param("s", $mail);
                 $stmt->execute();
                 $stmt->store_result();

                 if($stmt->num_rows > 0){
                     $response['error'] = true;
                     $response['message'] = 'User already registered';
                     $stmt->close();
                 }else{
                    $stmt = $obj->conn->prepare("INSERT INTO doctor (doctor_name, doctor_password, doctor_location, doctor_icon, doctor_mail, doctor_start_timing, doctor_end_timing, working_days, consult_fee, status, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    //$stmt = $obj->conn->prepare("INSERT INTO doctor (doctor_name) VALUES (?)");
                    $stmt->bind_param("sssssssssss", $username, $password, $location, $icon, $mail, $stime, $etime, $working_days, $fee, $status, $gender);
                    //$stmt->bind_param("s", $username);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT * FROM doctor WHERE doctor_mail = ?");
                        //$stmt = $obj->conn->prepare("SELECT doctor_name FROM doctor WHERE doctor_name = ?");
                        $stmt->bind_param("s",$mail);
                        $stmt->execute();
                        $stmt->bind_result($username, $location, $icon, $mail, $stime, $etime, $working_days, $fee, $status, $gender);
                        //$stmt->bind_result($username);
                        $stmt->fetch();

                        $user = array(
                            'username'=>$username,
                            'password'=>$password,
                            'location'=>$location,
                            'icon'=>$icon,
                            'mail'=>$mail,
                            'stime'=>$stime,
                            'etime'=>$etime,
                            'working_days'=>$working_days,
                            'fee'=>$fee,
                            'status'=>$status,
                            'gender'=>$gender
                        );

                        $stmt->close();
                        
                        $response['error'] = false;
                        $response['message'] = 'Doctor registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                 }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required parameters are not available';
            }
                
            break;

            case 'hospital_signup':
                if(isTheseParametersAvailable(array('hospital_name','hospital_icon','hospital_password','hospital_location','hospital_mail','hospital_start_timing','hospital_end_timing','working_days','status'))){
                $username = $_POST['hospital_name'];
                $icon = $_POST['hospital_icon'];
                $password = $_POST['hospital_password'];
                $location = $_POST['hospital_location'];
                $mail = $_POST['hospital_mail'];
                $stime = $_POST['hospital_start_timing'];
                $etime = $_POST['hospital_end_timing'];
                $working_days = $_POST['working_days'];
                $status = $_POST['status'];


                 $stmt = $obj->conn->prepare("SELECT * FROM hospital WHERE hospital_mail = ?");
                 $stmt->bind_param("s", $hospital_mail);
                 $stmt->execute();
                 $stmt->store_result();

                 if($stmt->num_rows > 0){
                     $response['error'] = true;
                     $response['message'] = 'Hospital already registered';
                     $stmt->close();
                 }else{
                    $stmt = $obj->conn->prepare("INSERT INTO hospital (hospital_name, hospital_icon, hospital_password, hospital_location, hospital_mail, hospital_start_timing, hospital_end_timing, working_days, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssssss", $username, $icon, $password, $location, $mail, $stime, $etime, $working_days, $status);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT  hospital_name, hospital_icon, hospital_password, hospital_location, hospital_mail, hospital_start_timing, hospital_end_timing, working_days, status FROM hospital WHERE hospital_mail = ?");
                        $stmt->bind_param("s",$hospital_mail);
                        $stmt->execute();
                        $stmt->bind_result($username, $icon, $password, $location, $mail, $stime, $etime, $working_days, $status);
                        $stmt->fetch();

                        $user = array(
                            'hospitalname'=>$username,
                            'hospitalicon'=>$icon,
                            'hospitallocation'=>$location,
                            'status'=>$status
                        );

                        $stmt->close();
                        
                        $response['error'] = false;
                        $response['status'] = 1;
                        $response['message'] = 'Hospital registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                 }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required parameters are not available';
            }
                
            break;
            
            case 'laboratory_signup':
                if(isTheseParametersAvailable(array('lab_name','lab_password','lab_icon','lab_mail','lab_address','lab_contact_person','lab_start_timing','lab_end_timing','lab_working_days','lab_consult_fee','status'))){
                $username = $_POST['lab_name'];
                $password = $_POST['lab_password'];
                $icon = $_POST['lab_icon'];
                $mail = $_POST['lab_mail'];
                $location = $_POST['lab_address'];
                $contact_person = $_POST['lab_contact_person'];
                $stime = $_POST['lab_start_timing'];
                $etime = $_POST['lab_end_timing'];
                $working_days = $_POST['lab_working_days'];
                $fee = $_POST['lab_consult_fee'];
                $status = $_POST['status'];


                 $stmt = $obj->conn->prepare("SELECT * FROM laboratories WHERE lab_mail = ?");
                 $stmt->bind_param("s", $lab_mail);
                 $stmt->execute();
                 $stmt->store_result();

                 if($stmt->num_rows > 0){
                     $response['error'] = true;
                     $response['message'] = 'laboratory already registered';
                     $stmt->close();
                 }else{
                    $stmt = $obj->conn->prepare("INSERT INTO laboratories (lab_name, lab_password, lab_icon, lab_mail, lab_address, lab_contact_person, lab_start_timing, lab_end_timing, lab_working_days, lab_consult_fee, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssssssss", $username, $password, $icon, $mail, $location, $contact_person, $stime, $etime, $working_days, $fee, $status);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT lab_name, lab_password, lab_icon, lab_mail, lab_address, lab_contact_person, lab_start_timing, lab_end_timing, lab_working_days, lab_consult_fee, status FROM laboratories WHERE lab_mail = ?");
                        $stmt->bind_param("s",$lab_mail);
                        $stmt->execute();
                        $stmt->bind_result($username, $password, $icon, $mail, $location, $contact_person, $stime, $etime, $working_days, $fee, $status);
                        $stmt->fetch();

                        $user = array(
                            'name'=>$username
                        );

                        $stmt->close();
                        
                        $response['error'] = false;
                        $response['status'] = 1;
                        $response['message'] = 'Loboratory registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                 }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required param`eters are not available';
            }
                
            break;
            
            case 'pharmacy_signup':
                if(isTheseParametersAvailable(array('pharmacy_name','pharmacy_password','pharmacy_location','pha_icon','pharmacy_mail','pharmacy_contact_person','pharmacy_start_timing','pharmacy_end_timing','pharmacy_working_days','status'))){
                $username = $_POST['pharmacy_name'];
                $password = $_POST['pharmacy_password'];
                $location = $_POST['pharmacy_location'];
                $icon = $_POST['pha_icon'];
                $mail = $_POST['pharmacy_mail'];
                $contact_person = $_POST['pharmacy_contact_person'];
                $stime = $_POST['pharmacy_start_timing'];
                $etime = $_POST['pharmacy_end_timing'];
                $working_days = $_POST['pharmacy_working_days'];
                $status = $_POST['status'];


                 $stmt = $obj->conn->prepare("SELECT * FROM pharmacy WHERE pharmacy_mail = ?");
                 $stmt->bind_param("s", $mail);
                 $stmt->execute();
                 $stmt->store_result();

                 if($stmt->num_rows > 0){
                     $response['error'] = true;
                     $response['message'] = 'Pharmacy already registered';
                     $stmt->close();
                 }else{
                    $stmt = $obj->conn->prepare("INSERT INTO pharmacy (pharmacy_name, pharmacy_password, pharmacy_location, pha_icon, pharmacy_mail, pharmacy_contact_person, pharmacy_start_timing, pharmacy_end_timing, pharmacy_working_days, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssss", $username, $password, $location, $icon, $mail, $contact_person, $stime, $etime, $working_days, $status);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT * FROM pharmacy WHERE pharmacy_mail = ?");
                        $stmt->bind_param("s",$mail);
                        $stmt->execute();
                        $stmt->bind_result($username, $password, $location, $icon, $mail, $contact_person, $stime, $etime, $working_days, $status);
                        $stmt->fetch();

                        $user = array(
                            'Pharmacy_name'=>$username
                        );

                        $stmt->close();
                        
                        $response['error'] = false;
                        $response['status'] = 1;
                        $response['message'] = 'Pharmacy registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                 }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required parameters are not available';
            }
                
            break;
            
            case 'inc_signup':
                if(isTheseParametersAvailable(array('inc_name','inc_password','inc_icon','inc_email','inc_location','inc_contact_person','inc_start_timing','inc_end_timing','inc_working_days','status'))){
                $username = $_POST['inc_name'];
                $password = $_POST['inc_password'];
                $icon = $_POST['inc_icon'];
                $mail = $_POST['inc_email'];
                $location = $_POST['inc_location'];
                $contact_person = $_POST['inc_contact_person'];
                $stime = $_POST['inc_start_timing'];
                $etime = $_POST['inc_end_timing'];
                $working_days = $_POST['inc_working_days'];
                $status = $_POST['status'];


                 $stmt = $obj->conn->prepare("SELECT * FROM inc WHERE inc_email = ?");
                 $stmt->bind_param("s", $mail);
                 $stmt->execute();
                 $stmt->store_result();

                 if($stmt->num_rows > 0){
                     $response['error'] = true;
                     $response['message'] = 'Insurance company already registered';
                     $stmt->close();
                 }else{
                    $stmt = $obj->conn->prepare("INSERT INTO inc (inc_name, inc_password, inc_icon, inc_email, inc_location, inc_contact_person, inc_start_timing, inc_end_timing, inc_working_days, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssss", $username, $password, $icon, $mail, $location, $contact_person, $stime, $etime, $working_days, $status);

                    if($stmt->execute()){
                        $stmt = $obj->conn->prepare("SELECT  inc_name, inc_icon, inc_password, inc_location, inc_email, inc_contact_person, inc_start_timing, inc_end_timing, inc_working_days, status FROM inc WHERE inc_email = ?");
                        $stmt->bind_param("s",$mail);
                        $stmt->execute();
                        $stmt->bind_result($username, $icon, $password, $location, $mail, $contact_person, $stime, $etime, $working_days, $status);
                        $stmt->fetch();

                        $user = array(
                            'Insurance_company_name'=>$username
                        );

                        $stmt->close();
                        
                        $response['error'] = false;
                        $response['status'] = 1;
                        $response['message'] = 'Insurance Company registered successfully';
                        $response['user'] = $user;
                        header('Content-type: multipart/form-data');
                        echo json_encode($response);
                    }
                 }

            }else{
                $response['error'] = true;
                $response['status'] = 0;
                $response['message'] = 'required parameters are not available';
            }
                
            break;
            
        case 'login':

            if(isTheseParametersAvailable(array('email_id', 'password'))){

                $email_id = $_POST['email_id'];
                $password = md5($_POST['password']);
                $stmt = $obj->conn->prepare("SELECT * FROM end_users WHERE email_id = ? AND password = ?");
                $stmt->bind_param("ss",$email_id, $password);
                
                $stmt->execute();
                $stmt->store_result();
                if($stmt->num_rows > 0){

                    $stmt->bind_result($id, $username, $email_id, $mobile_no, $location, $profile_image, $password);
                    $stmt->fetch();
                     
                      
                    $user = array(
                        'id'=>$id,
                        'username'=>$username,
                        'email_id'=>$email_id,
                        'mobile_no'=>$mobile_no,
                        'location'=>$location,
                        'profile_image'=>$profile_image,
                        'password'=>$password
                    );

                    $response['error'] = false;    
                    $response['status'] = '1';
                    $response['message'] = 'Login successfull';
                    header('Content-type: multipart/form-data');
                    echo json_encode($response);
                     
                }else{
                    $response['error'] = true;
                    $response['status'] = '0';
                    $response['message'] = 'Invalid email or password';
                    header('Content-type: multipart/form-data');
                    echo json_encode($response);
                }
            }
            break;
        
        case 'specialization':
        $sql="SELECT * FROM category WHERE status='1'";
        
        $query=mysqli_query($obj->conn,$sql);
        
        while($result=mysqli_fetch_assoc($query))
        {
            $category[] = array("category_id"=>$result['category_id'],
            "category_name"=>$result['category_name'],
            "icon"=>$result['icon']);
        }
            header('Content-type: multipart/form-data');
            echo json_encode(array('Items' => $category));
            break;
        
        case 'hospital':
        $sql="SELECT t1.*, t2.category_name, t2.category_id FROM hospital as t1 INNER JOIN category as t2 ON t1.category_id = t2.category_id WHERE t1.status = '1'";
        $query=mysqli_query($obj->conn,$sql);
        while($result=mysqli_fetch_assoc($query))
        {
            $hospital[] = array("hospital_id"=>$result['hospital_id'],
            "hospital_name"=>$result['hospital_name'],
            "category_name"=>$result['category_name'],
            "hospital_location"=>$result['hospital_location'],
            "hospital_icon"=>$result['hospital_icon']);
        }
            header('Content-type: multipart/form-data');
            echo json_encode(array('Items' => $hospital));
        break;
        
        case 'doctor':
        $sql="SELECT t1.*, t2.category_name, t2.category_id FROM doctor as t1 INNER JOIN category as t2 ON t1.category_id = t2.category_id WHERE t1.status = '1'";
        $query=mysqli_query($obj->conn,$sql);
        while($result=mysqli_fetch_assoc($query))
        {
            $doctor[] = array("doctor_id"=>$result['doctor_id'],
            "doctor_name"=>$result['doctor_name'],
            "category_name"=>$result['category_name'],
            "doctor_location"=>$result['doctor_location'],
            "doctor_icon"=>$result['doctor_icon']
            );
        }
            header('Content-type: multipart/form-data');
            echo json_encode(array('Items' => $doctor));
        break;
        
        case 'laboratory':
        $sql="SELECT t1.*, t2.hospital_name, t2.hospital_id FROM laboratories as t1 INNER JOIN hospital as t2 ON t1.hospital_id = t2.hospital_id WHERE t1.status = '1'";
        $query=mysqli_query($obj->conn,$sql);
        while($result=mysqli_fetch_assoc($query))
        {
            $laboratory[] = array("lab_id"=>$result['lab_id'],
            "lab_name"=>$result['lab_name'],
            "hospital_name"=>$result['hospital_name'],
            "lab_icon"=>$result['lab_icon'],
            "lab_address"=>$result['lab_address'],
            "lab_contact_person"=>$result['lab_contact_person'],
            "lab_timing"=>$result['lab_timing'],
            "lab_working_days"=>$result['lab_working_days']
            );
        }
            header('Content-type: multipart/form-data');
            echo json_encode(array('Items' => $laboratory));
        break;
        
        case 'pharmacy':
        $sql="SELECT t1.*, t2.hospital_name, t2.hospital_id FROM pharmacy as t1 INNER JOIN hospital as t2 ON t1.hospital_id = t2.hospital_id WHERE t1.status = '1'";
        $query=mysqli_query($obj->conn,$sql);
        while($result=mysqli_fetch_assoc($query))
        {
            $pharmacy[] = array("pharmacy_id"=>$result['pharmacy_id'],
            "pharmacy_name"=>$result['pharmacy_name'],
            "pha_icon"=>$result['pha_icon'],
            "hospital_name"=>$result['hospital_name'],
            "pharmacy_location"=>$result['pharmacy_location'],
            "pharmacy_contact_person"=>$result['pharmacy_contact_person'],
            "pharmacy_timing"=>$result['pharmacy_timing'],
            "pharmacy_working_days"=>$result['pharmacy_working_days']
            );
        }
            header('Content-type: multipart/form-data');
            echo json_encode(array('Items' => $pharmacy));
        break;
        
        case 'insurance':
        $sql="SELECT t1.*, t2.hospital_name, t2.hospital_id FROM inc as t1 INNER JOIN hospital as t2 ON t1.hospital_id = t2.hospital_id WHERE t1.status = '1'";
        $query=mysqli_query($obj->conn,$sql);
        while($result=mysqli_fetch_assoc($query))
        {
            $insurance[] = array("inc_id"=>$result['inc_id'],
            "inc_name"=>$result['inc_name'],
            "hospital_name"=>$result['hospital_name'],
            "inc_email"=>$result['inc_email'],
            "inc_location"=>$result['inc_location'],
            "inc_contact_person"=>$result['inc_contact_person'],
            "inc_timing"=>$result['inc_timing'],
            "inc_working_days"=>$result['inc_working_days']
            );
        }
            header('Content-type: multipart/form-data');
            echo json_encode(array('Items' => $insurance));
        break;
        
        

        default:
            $response['error'] = true;
            $response['message'] = 'Invalid Operation Called';
    }
}else{
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}
 

//json_decode(file_get_contents("php://input"));

 function isTheseParametersAvailable($params){

    foreach($params as $param){
        if(!isset($_POST[$param])){
            return false;
        }
    }
    return true;
 }